<?php

namespace App\Http\Controllers;

use App\Imports\ImportStock;
use App\Models\Barang;
use App\Models\OpeningStock;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StockController extends Controller
{
    //
    public function stockla(Request $request)
    {    
        if($request->ajax()){
            $stock = Stock::leftJoin('barang','barang.barang_id','=','stock.barang_id')
            ->select('stock_id','barang.code_barang','barang.nama_barang','barang.spek','barang.satuan','stock','stock.factory','stock.keterangan')
            ->where('status_stock','LA')
            ->where('factory',auth()->user()->factory)
            ->orderBy('code_barang','asc');
            return DataTables::of($stock)->make(true);
        }
        if(auth()->user()->factory == 1){
            $barang = Barang::where('status_barang','LA 1')->get();
        } else {
            $barang = Barang::where('status_barang','LA 2')->get();
        }
        return view('stocks.stockla',compact('barang'));
    }

    public function stockpp(Request $request)
    {
        if($request->ajax()){
            $stock = Stock::leftJoin('barang','barang.barang_id','=','stock.barang_id')
            ->select('stock_id','barang.code_barang','barang.nama_barang','barang.spek','barang.satuan','stock','stock.factory','stock.keterangan','min_stock','std_stock')
            ->where('status_stock','PP')
            ->orderBy('code_barang','asc');
            // ->get();
            return DataTables::of($stock)->make(true);
        }
        $barang = Barang::where('status_barang','PP')->get();
        return view('stocks.stockpp',compact('barang'));
    }

    public function stockeva(Request $request)
    {
        if($request->ajax()){
            $stock = Stock::leftJoin('barang','barang.barang_id','=','stock.barang_id')
            ->select('stock_id','barang.code_barang','barang.nama_barang','barang.spek','barang.satuan','stock','stock.factory','stock.keterangan','min_stock','std_stock')
            ->where('status_stock','EVA')
            ->orderBy('code_barang','asc');
            // ->get();
            return DataTables::of($stock)->make(true);
        }
        $barang = Barang::where('status_barang','EVA')->get();
        return view('stocks.stockeva',compact('barang'));
    }

    public function stockpo(Request $request)
    {
        if($request->ajax()){
            $stock = Stock::leftJoin('barang','barang.barang_id','=','stock.barang_id')
            ->select('stock_id','barang.code_barang','barang.nama_barang','barang.spek','barang.satuan','stock','stock.factory','stock.keterangan','min_stock','std_stock')
            ->where('status_stock','PO')
            ->orderBy('code_barang','asc');
            // ->get();
            return DataTables::of($stock)->make(true);
        }
        $barang = Barang::where('status_barang','PO')->get();
        return view('stocks.stockpo',compact('barang'));
    }

    public function stockmdf(Request $request)
    {
        if($request->ajax()){
            $stock = Stock::leftJoin('barang','barang.barang_id','=','stock.barang_id')
            ->select('stock_id','barang.code_barang','barang.nama_barang','barang.spek','barang.satuan','stock','stock.factory','stock.keterangan','min_stock','std_stock')
            ->where('status_stock','MDF')
            ->orderBy('barang.code_barang','asc');
            // ->get();
            return DataTables::of($stock)->make(true);
        }
        $barang = Barang::where('status_barang','MDF')->get();
        $supplier = Supplier::select('supplier_id','supplier_name')->get();
        return view('stocks.stockmdf',compact('barang','supplier'));
    }

    public function stockga(Request $request)
    {
        if($request->ajax()){
            $stock = Stock::leftJoin('barang','barang.barang_id','=','stock.barang_id')
            ->select('stock_id','barang.code_barang','barang.nama_barang','barang.spek','barang.satuan','stock','stock.factory','stock.keterangan','min_stock','std_stock')
            ->where('status_stock','GA')
            ->orderBy('barang.code_barang','asc');
            // ->get();
            return DataTables::of($stock)->make(true);
        }
        $barang = Barang::where('status_barang','GA')->get();
        return view('stocks.stockga',compact('barang'));
    }

    public function stockiss(Request $request)
    {
        if($request->ajax()){
            $stock = Stock::leftJoin('barang','barang.barang_id','=','stock.barang_id')
            ->select('stock_id','barang.code_barang','barang.nama_barang','barang.spek','barang.satuan','stock','stock.factory','stock.keterangan','min_stock','std_stock')
            ->where('status_stock','ISS')
            ->orderBy('barang.code_barang','asc');
            // ->get();
            return DataTables::of($stock)->make(true);
        }
        $barang = Barang::where('status_barang','ISS')->get();
        return view('stocks.stockiss',compact('barang'));
    }

    public function create(Request $request)
    {
        $barang = Barang::where('code_barang',$request->code_barang)->first();
        switch($request->status_stock){
            case 'LA 1':
            $status = 'LA';
            break;
            case 'LA 2':
            $status = 'LA';
            break;
            case 'PP':
            $status = 'PP';
            break;
            case 'EVA':
            $status = 'EVA';
            break;
            case 'PO':
            $status = 'PO';
            break;
            case 'MDF':
            $status = 'MDF';
            break;
            case 'GA':
            $status = 'GA';
            break;
            case 'ISS':
            $status = 'ISS';
            break;
        }
        $bulan = Carbon::now()->format('m');
        $tahun = Carbon::now()->format('Y');
        $stock = Stock::where('barang_id',$barang->barang_id)->where('status_stock',$status)->first();
        $opening = OpeningStock::where('barang_id',$barang->barang_id)->whereMonth('tanggal_opening',$bulan)->whereYear('tanggal_opening',$tahun)->first();
        if(auth()->user()->role == 'Admin MDF' || auth()->user()->role == 'User MDF' || auth()->user()->role == 'Administrator' || auth()->user()->role == 'Admin GA' || auth()->user()->role == 'Admin PP' || auth()->user()->role == 'Admin LA'){
            if($stock){
                if(!$opening){
                    OpeningStock::create([
                        'barang_id' => $barang->barang_id,
                        'tanggal_opening' => Carbon::now(),
                        'stock_opening' => $stock ? $stock->stock : 0,
                    ]);
                }
                $stock->stock = $stock->stock + $request->stock;
                $stock->save();
                Transaction::create([
                    'barang_id' => $barang->barang_id,
                    'nik' => auth()->user()->username,
                    'namapic' => auth()->user()->name,
                    'section' => auth()->user()->section,
                    'no_lane' => '-',
                    'quantity' => $request->stock,
                    'tanggal' => Carbon::now(),
                    'status' => 'IN',
                    'supplier_name' => $request->supplier_name,
                    'factory' => auth()->user()->factory,
                    'keterangan' => $request->keterangan,
                ]);
            } else {
                Stock::create([
                    'barang_id' => $barang->barang_id,
                    'stock' => $request->stock,
                    'min_stock' => $request->min_stock,
                    'std_stock' => $request->std_stock,
                    'status_stock' => $status,
                    'factory' => auth()->user()->factory,
                    'keterangan' => $request->keterangan,
                ]);
                if(!$opening){
                    OpeningStock::create([
                        'barang_id' => $barang->barang_id,
                        'tanggal_opening' => Carbon::now(),
                        'stock_opening' => $stock ? $stock->stock : 0,
                    ]);
                }
                Transaction::create([
                    'barang_id' => $barang->barang_id,
                    'nik' => auth()->user()->username,
                    'namapic' => auth()->user()->name,
                    'section' => auth()->user()->section,
                    'no_lane' => '-',
                    'quantity' => $request->stock,
                    'tanggal' => Carbon::now(),
                    'status' => 'IN',
                    'supplier_name' => $request->supplier_name,
                    'factory' => auth()->user()->factory,
                    'keterangan' => $request->keterangan,
                ]);
            }
        } else {
            if($stock){
                $stock->stock = $stock->stock + $request->stock;
                $stock->save();
                if(!$opening){
                    OpeningStock::create([
                        'barang_id' => $barang->barang_id,
                        'tanggal_opening' => Carbon::now(),
                        'stock_opening' => $stock ? $stock->stock : 0,
                    ]);
                }
                Transaction::create([
                    'barang_id' => $barang->barang_id,
                    'nik' => auth()->user()->username,
                    'namapic' => auth()->user()->name,
                    'section' => auth()->user()->section,
                    'no_lane' => '-',
                    'quantity' => $request->stock,
                    'tanggal' => Carbon::now(),
                    'status' => 'IN',
                    'factory' => auth()->user()->factory,
                    'keterangan' => $request->keterangan,
                ]);
            } else {
                Stock::create([
                    'barang_id' => $barang->barang_id,
                    'stock' => $request->stock,
                    'status_stock' => $status,
                    'factory' => auth()->user()->factory,
                    'keterangan' => $request->keterangan,
                ]);
                if(!$opening){
                    OpeningStock::create([
                        'barang_id' => $barang->barang_id,
                        'tanggal_opening' => Carbon::now(),
                        'stock_opening' => $stock ? $stock->stock : 0,
                    ]);
                }
                Transaction::create([
                    'barang_id' => $barang->barang_id,
                    'nik' => auth()->user()->username,
                    'namapic' => auth()->user()->name,
                    'section' => auth()->user()->section,
                    'no_lane' => '-',
                    'quantity' => $request->stock,
                    'tanggal' => Carbon::now(),
                    'status' => 'IN',
                    'factory' => auth()->user()->factory,
                    'keterangan' => $request->keterangan,
                ]);
            }
        }
        return response()->json(['status'=>201,'message'=>'Create Stock Success']);
    }

    public function Import(Request $request)
    {
        $validasi = $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
        $file = $request->file('file');
        $filename = time().'.'.$file->getClientOriginalExtension();
        try{
            \Maatwebsite\Excel\Facades\Excel::import(new ImportStock, $file);
            return response()->json(['status'=>201,'message'=>'Barang Imported Successfully']);
        } catch(\Exception $e){
            return response()->json(['status'=>500,'message'=>$e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::find($id);
        if($stock){
            $stock->stock = $request->stock;
            if($request->filled('min_stock')){
                $stock->min_stock = $request->min_stock;
            }
            if($request->filled('std_stock')){
                $stock->std_stock = $request->std_stock;
            }
            $stock->keterangan = $request->keterangan;
            $stock->save();
            return response()->json(['status'=>201,'message'=>'Update Stock Success']);
        } else {
            return response()->json(['status'=>404,'message'=>'Stock Not Found']);
        }
    }

    public function delete(Request $request, $id)
    {
        $stock = Stock::find($id);
        if($stock){
            $stock->delete();
            return response()->json(['status'=>200,'message'=>'Delete Stock Success']);
        } else {
            return response()->json(['status'=>404,'message'=>'Stock Not Found']);
        }
    }
}
