<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    //
    public function reportla(Request $request)
    {
        if ($request->ajax()) {
            $report = Barang::leftJoin('stock','barang.barang_id','=','stock.barang_id')
                ->leftJoin('transactions','transactions.barang_id','=','barang.barang_id')
                ->select(
                    'barang.barang_id',
                    'code_barang',
                    'nama_barang',
                    'spek',
                    'stock.factory',
                    'stock',
                    'status_stock',
                    'satuan',
                    DB::raw('SUM(CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END) as qtyout')
                );

            if ($request->input('start_date') && $request->input('end_date')) {
                $report->whereBetween('tanggal', [$request->start_date, $request->end_date])
                ->whereBetween('opening_stock.tanggal_opening', [$request->start_date, $request->end_date]);
            }

            // Filter berdasarkan role user
            if(auth()->user()->role == 'Administrator'){
                $report->where('status_stock', 'LA');
            } else {
                $report->where('status_stock', 'LA')
                ->where('stock.factory', auth()->user()->factory);
            }

            $report->groupBy(
                'barang.barang_id',
                'code_barang',
                'nama_barang',
                'spek',
                'stock.factory',
                'stock',
                'status_stock',
                'satuan',
            );

            return DataTables::of($report)->make(true);
        }

        return view('reports.reportla');
    }

    public function DetailLA(Request $request,$id,$startdate,$enddate)
    {
        $barang = Barang::where('code_barang',$id)->first();
        $title = 'Detail '.$barang->code_barang.'-'.$barang->nama_barang;
        if($request->ajax()){
            $report = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
                ->select(
                    'transaction_id',
                    'code_barang',
                    'nama_barang',
                    'spek',
                    'satuan',
                    'status_barang',
                    'factory',
                    'quantity',
                    'tanggal'
                )
                ->where('transactions.barang_id',$barang->barang_id)
                ->where('status','OUT')
                ->whereBetween('tanggal',[$startdate,$enddate])
                ->orderBy('tanggal','asc');
            return DataTables::of($report)->make(true);
        }
        return view('reports.detailla',compact('title','id','startdate','enddate'));
    }

    public function reportpp(Request $request)
    {
        if ($request->ajax()) {
            $report = Barang::leftJoin('transactions','transactions.barang_id','=','barang.barang_id')
                ->join('opening_stock', function($join) use ($request) {
                    $join->on('barang.barang_id', '=', 'opening_stock.barang_id')
                    ->whereMonth('opening_stock.tanggal_opening',Carbon::parse($request->start_date)->month)
                    ->whereYear('opening_stock.tanggal_opening',Carbon::parse($request->start_date)->year);
                })            
                ->select(
                    'barang.barang_id',
                    'code_barang',
                    'nama_barang',
                    'spek',
                    'transactions.factory',
                    'opening_stock.stock_opening as stock',
                    'status_barang',
                    'satuan',
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END),0) as qtyout'),
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.status = "IN" THEN transactions.quantity ELSE 0 END),0) as qtyin')
                );

            if ($request->input('start_date') && $request->input('end_date')) {
                $report->whereBetween('tanggal', [$request->start_date, $request->end_date])
                ->whereBetween('opening_stock.tanggal_opening', [$request->start_date, $request->end_date]);
            }
            $report->where('status_barang', 'PP');
            $report->groupBy(
                'barang.barang_id',
                'code_barang',
                'nama_barang',
                'spek',
                'transactions.factory',
                'status_barang',
                'satuan',
                'opening_stock.stock_opening'
            );

            return DataTables::of($report)->make(true);
        }
        return view('reports.reportpp');
    }

    public function DetailPP(Request $request, $id, $startdate, $enddate)
    {
        $barang = Barang::where('code_barang', $id)->first();
        $title = 'Detail ' . $barang->code_barang . '-' . $barang->nama_barang;
        if ($request->ajax()) {
            $report = Transaction::leftJoin('barang', 'barang.barang_id', '=', 'transactions.barang_id')
                ->join('opening_stock', function($join) use ($startdate) {
                    $join->on('barang.barang_id', '=', 'opening_stock.barang_id')
                    ->whereMonth('opening_stock.tanggal_opening',Carbon::parse($startdate)->month)
                    ->whereYear('opening_stock.tanggal_opening',Carbon::parse($startdate)->year);
                })
                ->select(
                    'transaction_id',
                    'barang.code_barang',
                    'barang.nama_barang',
                    'barang.spek',
                    'barang.satuan',
                    'status_barang',
                    'opening_stock.stock_opening as stock',
                    'factory',
                    DB::raw('CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END as qtyout'),
                    DB::raw('CASE WHEN transactions.status = "IN" THEN transactions.quantity ELSE 0 END as qtyin'),
                    'tanggal'
                )
                ->where('transactions.barang_id', $barang->barang_id)
                ->whereBetween('tanggal', [$startdate, $enddate])
                ->whereNotNull('opening_stock.stock_opening')
                ->groupBy(
                    'transaction_id',
                    'opening_stock.stock_opening'
                )
                ->orderBy('tanggal', 'asc');

            return DataTables::of($report)->make(true);
        }
        return view('reports.detailpp', compact('title', 'id', 'startdate', 'enddate'));
    }

    public function reporteva(Request $request)
    {
        if ($request->ajax()) {
            $report = Barang::leftJoin('transactions','transactions.barang_id','=','barang.barang_id')
                ->join('opening_stock', function($join) use ($request) {
                    $join->on('barang.barang_id', '=', 'opening_stock.barang_id')
                    ->whereMonth('opening_stock.tanggal_opening',Carbon::parse($request->start_date)->month)
                    ->whereYear('opening_stock.tanggal_opening',Carbon::parse($request->start_date)->year);
                })            
                ->select(
                    'barang.barang_id',
                    'code_barang',
                    'nama_barang',
                    'spek',
                    'transactions.factory',
                    'opening_stock.stock_opening as stock',
                    'status_barang',
                    'satuan',
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END),0) as qtyout'),
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.status = "IN" THEN transactions.quantity ELSE 0 END),0) as qtyin')
                );

            if ($request->input('start_date') && $request->input('end_date')) {
                $report->whereBetween('tanggal', [$request->start_date, $request->end_date])
                ->whereBetween('opening_stock.tanggal_opening', [$request->start_date, $request->end_date]);
            }
            $report->where('status_barang', 'EVA');
            $report->groupBy(
                'barang.barang_id',
                'code_barang',
                'nama_barang',
                'spek',
                'transactions.factory',
                'status_barang',
                'satuan',
                'opening_stock.stock_opening'
            );

            return DataTables::of($report)->make(true);
        }
        return view('reports.reporteva');
    }

    public function DetailEVA(Request $request, $id, $startdate, $enddate)
    {
        $barang = Barang::where('code_barang', $id)->first();
        $title = 'Detail ' . $barang->code_barang . '-' . $barang->nama_barang;
        if ($request->ajax()) {
            $report = Transaction::leftJoin('barang', 'barang.barang_id', '=', 'transactions.barang_id')
                ->join('opening_stock', function($join) use ($startdate) {
                    $join->on('barang.barang_id', '=', 'opening_stock.barang_id')
                    ->whereMonth('opening_stock.tanggal_opening',Carbon::parse($startdate)->month)
                    ->whereYear('opening_stock.tanggal_opening',Carbon::parse($startdate)->year);
                })
                ->select(
                    'transaction_id',
                    'barang.code_barang',
                    'barang.nama_barang',
                    'barang.spek',
                    'barang.satuan',
                    'status_barang',
                    'opening_stock.stock_opening as stock',
                    'factory',
                    DB::raw('CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END as qtyout'),
                    DB::raw('CASE WHEN transactions.status = "IN" THEN transactions.quantity ELSE 0 END as qtyin'),
                    'tanggal'
                )
                ->where('transactions.barang_id', $barang->barang_id)
                ->whereBetween('tanggal', [$startdate, $enddate])
                ->whereNotNull('opening_stock.stock_opening')
                ->groupBy(
                    'transaction_id',
                    'opening_stock.stock_opening'
                )
                ->orderBy('tanggal', 'asc');

            return DataTables::of($report)->make(true);
        }
        return view('reports.detaileva', compact('title', 'id', 'startdate', 'enddate'));
    }

    public function reportpo(Request $request)
    {
        if ($request->ajax()) {
            $report = Barang::leftJoin('transactions','transactions.barang_id','=','barang.barang_id')
                ->join('opening_stock', function($join) use ($request) {
                    $join->on('barang.barang_id', '=', 'opening_stock.barang_id')
                    ->whereMonth('opening_stock.tanggal_opening',Carbon::parse($request->start_date)->month)
                    ->whereYear('opening_stock.tanggal_opening',Carbon::parse($request->start_date)->year);
                })            
                ->select(
                    'barang.barang_id',
                    'code_barang',
                    'nama_barang',
                    'spek',
                    'transactions.factory',
                    'opening_stock.stock_opening as stock',
                    'status_barang',
                    'satuan',
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END),0) as qtyout'),
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.status = "IN" THEN transactions.quantity ELSE 0 END),0) as qtyin')
                );

            if ($request->input('start_date') && $request->input('end_date')) {
                $report->whereBetween('tanggal', [$request->start_date, $request->end_date])
                ->whereBetween('opening_stock.tanggal_opening', [$request->start_date, $request->end_date]);
            }
            $report->where('status_barang', 'PO');
            $report->groupBy(
                'barang.barang_id',
                'code_barang',
                'nama_barang',
                'spek',
                'transactions.factory',
                'status_barang',
                'satuan',
                'opening_stock.stock_opening'
            );

            return DataTables::of($report)->make(true);
        }
        return view('reports.reportpo');
    }

    public function DetailPO(Request $request, $id, $startdate, $enddate)
    {
        $barang = Barang::where('code_barang', $id)->first();
        $title = 'Detail ' . $barang->code_barang . '-' . $barang->nama_barang;
        if ($request->ajax()) {
            $report = Transaction::leftJoin('barang', 'barang.barang_id', '=', 'transactions.barang_id')
                ->join('opening_stock', function($join) use ($startdate) {
                    $join->on('barang.barang_id', '=', 'opening_stock.barang_id')
                    ->whereMonth('opening_stock.tanggal_opening',Carbon::parse($startdate)->month)
                    ->whereYear('opening_stock.tanggal_opening',Carbon::parse($startdate)->year);
                })
                ->select(
                    'transaction_id',
                    'barang.code_barang',
                    'barang.nama_barang',
                    'barang.spek',
                    'barang.satuan',
                    'status_barang',
                    'opening_stock.stock_opening as stock',
                    'factory',
                    DB::raw('CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END as qtyout'),
                    DB::raw('CASE WHEN transactions.status = "IN" THEN transactions.quantity ELSE 0 END as qtyin'),
                    'tanggal'
                )
                ->where('transactions.barang_id', $barang->barang_id)
                ->whereBetween('tanggal', [$startdate, $enddate])
                ->whereNotNull('opening_stock.stock_opening')
                ->groupBy(
                    'transaction_id',
                    'opening_stock.stock_opening'
                )
                ->orderBy('tanggal', 'asc');

            return DataTables::of($report)->make(true);
        }
        return view('reports.detailpo', compact('title', 'id', 'startdate', 'enddate'));
    }

    public function reportmdf(Request $request)
    {
        if ($request->ajax()) {
            $report = Barang::leftJoin('transactions','transactions.barang_id','=','barang.barang_id')
                ->join('opening_stock', function($join) use ($request) {
                    $join->on('barang.barang_id', '=', 'opening_stock.barang_id')
                    ->whereMonth('opening_stock.tanggal_opening',Carbon::parse($request->start_date)->month)
                    ->whereYear('opening_stock.tanggal_opening',Carbon::parse($request->start_date)->year);
                })            
                ->select(
                    'barang.barang_id',
                    'code_barang',
                    'nama_barang',
                    'spek',
                    'transactions.factory',
                    'opening_stock.stock_opening as stock',
                    'status_barang',
                    'satuan',
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END),0) as qtyout'),
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.status = "IN" THEN transactions.quantity ELSE 0 END),0) as qtyin')
                );

            if ($request->input('start_date') && $request->input('end_date')) {
                $report->whereBetween('tanggal', [$request->start_date, $request->end_date])
                ->whereBetween('opening_stock.tanggal_opening', [$request->start_date, $request->end_date]);
            }
            $report->where('status_barang', 'MDF');
            $report->groupBy(
                'barang.barang_id',
                'code_barang',
                'nama_barang',
                'spek',
                'transactions.factory',
                'status_barang',
                'satuan',
                'opening_stock.stock_opening'
            );

            return DataTables::of($report)->make(true);
        }

        return view('reports.reportmdf');
    }

    public function DetailMDF(Request $request, $id, $startdate, $enddate)
    {
        $barang = Barang::where('code_barang', $id)->first();
        $title = 'Detail ' . $barang->code_barang . '-' . $barang->nama_barang;
        if ($request->ajax()) {
            $report = Transaction::leftJoin('barang', 'barang.barang_id', '=', 'transactions.barang_id')
                ->join('opening_stock', function($join) use ($startdate) {
                    $join->on('barang.barang_id', '=', 'opening_stock.barang_id')
                    ->whereMonth('opening_stock.tanggal_opening',Carbon::parse($startdate)->month)
                    ->whereYear('opening_stock.tanggal_opening',Carbon::parse($startdate)->year);
                })
                ->select(
                    'transaction_id',
                    'barang.code_barang',
                    'barang.nama_barang',
                    'barang.spek',
                    'barang.satuan',
                    'status_barang',
                    'opening_stock.stock_opening as stock',
                    'factory',
                    DB::raw('CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END as qtyout'),
                    DB::raw('CASE WHEN transactions.status = "IN" THEN transactions.quantity ELSE 0 END as qtyin'),
                    'tanggal'
                )
                ->where('transactions.barang_id', $barang->barang_id)
                ->whereBetween('tanggal', [$startdate, $enddate])
                ->whereNotNull('opening_stock.stock_opening')
                ->groupBy(
                    'transaction_id',
                    'opening_stock.stock_opening'
                )
                ->orderBy('tanggal', 'asc');

            return DataTables::of($report)->make(true);
        }
        return view('reports.detailmdf', compact('title', 'id', 'startdate', 'enddate'));
    }

    public function reportga(Request $request)
    {
        if ($request->ajax()) {
            $report = Barang::leftJoin('transactions','transactions.barang_id','=','barang.barang_id')
                ->join('opening_stock','barang.barang_id','=','opening_stock.barang_id')
                ->select(
                    'barang.barang_id',
                    'code_barang',
                    'nama_barang',
                    'spek',
                    'transactions.factory',
                    'opening_stock.stock_opening as stock',
                    'status_barang',
                    'satuan',
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END),0) as qtyout'),
                    DB::raw('COALESCE(SUM(CASE WHEN transactions.status = "IN" THEN transactions.quantity ELSE 0 END),0) as qtyin')
                );

            if ($request->input('start_date') && $request->input('end_date')) {
                $report->whereBetween('tanggal', [$request->start_date, $request->end_date])
                ->whereBetween('opening_stock.tanggal_opening', [$request->start_date, $request->end_date]);
            }
            $report->where('status_barang', 'GA');
            $report->groupBy(
                'barang.barang_id',
                'code_barang',
                'nama_barang',
                'spek',
                'transactions.factory',
                'status_barang',
                'satuan',
                'opening_stock.stock_opening'
            );

            return DataTables::of($report)->make(true);
        }

        return view('reports.reportga');
    }

    public function DetailGA(Request $request,$id,$startdate,$enddate)
    {
        $barang = Barang::where('code_barang', $id)->first();
        $title = 'Detail ' . $barang->code_barang . '-' . $barang->nama_barang;
        if ($request->ajax()) {
            $report = Transaction::leftJoin('barang', 'barang.barang_id', '=', 'transactions.barang_id')
                ->join('opening_stock', 'barang.barang_id', '=', 'opening_stock.barang_id')
                ->select(
                    'transaction_id',
                    'barang.code_barang',
                    'barang.nama_barang',
                    'barang.spek',
                    'barang.satuan',
                    'status_barang',
                    'opening_stock.stock_opening as stock',
                    'factory',
                    DB::raw('CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END as qtyout'),
                    DB::raw('CASE WHEN transactions.status = "IN" THEN transactions.quantity ELSE 0 END as qtyin'),
                    'tanggal'
                )
                ->where('transactions.barang_id', $barang->barang_id)
                ->whereBetween('tanggal', [$startdate, $enddate])
                ->whereNotNull('opening_stock.stock_opening')
                ->groupBy(
                    'transaction_id',
                    'opening_stock.stock_opening'
                )
                ->orderBy('tanggal', 'asc');

            return DataTables::of($report)->make(true);
        }
        return view('reports.detailmdf', compact('title', 'id', 'startdate', 'enddate'));
    }

    public function reportiss(Request $request)
    {
        if ($request->ajax()) {
            $report = Barang::leftJoin('stock','barang.barang_id','=','stock.barang_id')
                ->leftJoin('transactions','transactions.barang_id','=','barang.barang_id')
                ->select(
                    'barang.barang_id',
                    'code_barang',
                    'nama_barang',
                    'spek',
                    'stock.factory',
                    'stock',
                    'status_stock',
                    'satuan',
                    DB::raw('SUM(CASE WHEN transactions.status = "OUT" THEN transactions.quantity ELSE 0 END) as qtyout')
                );

            if ($request->input('start_date') && $request->input('end_date')) {
                $report->whereBetween('tanggal', [$request->start_date, $request->end_date])
                ->whereBetween('opening_stock.tanggal_opening', [$request->start_date, $request->end_date]);
            }

            // Filter berdasarkan role user
            if(auth()->user()->role != 'Administrator'){
                $report->where('status_stock', 'ISS');
            }

            $report->groupBy(
                'barang.barang_id',
                'code_barang',
                'nama_barang',
                'spek',
                'stock.factory',
                'stock',
                'status_stock',
                'satuan',
            );

            return DataTables::of($report)->make(true);
        }

        return view('reports.reportiss');
    }

    public function DetailISS(Request $request,$id,$startdate,$enddate)
    {
        $barang = Barang::where('code_barang',$id)->first();
        $title = 'Detail '.$barang->code_barang.'-'.$barang->nama_barang;
        if($request->ajax()){
            $report = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
                ->select(
                    'transaction_id',
                    'barang.code_barang',
                    'barang.nama_barang',
                    'barang.spek',
                    'barang.satuan',
                    'status_barang',
                    'factory',
                    'quantity',
                    'tanggal'
                )
                ->where('transactions.barang_id',$barang->barang_id)
                ->where('status','OUT')
                ->whereBetween('tanggal',[$startdate,$enddate])
                ->orderBy('tanggal','asc');
            return DataTables::of($report)->make(true);
        }
        return view('reports.detailiss',compact('title','id','startdate','enddate'));
    }

    public function create()
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function delete(Request $request, $id)
    {

    }
}
