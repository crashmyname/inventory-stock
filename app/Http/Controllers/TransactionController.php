<?php

namespace App\Http\Controllers;

use App\Mail\Notification;
use App\Models\Barang;
use App\Models\Lane;
use App\Models\OpeningStock;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    //
    public function index(Request $request)
    {
        switch(true){
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 1:
                $sect = 'LA 1';
            break;
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 2:
                $sect = 'LA 2';
            break;
            case auth()->user()->role == 'Admin PP':
                $sect = 'PP';
            break;
            case auth()->user()->role == 'Admin EVA':
                $sect = 'EVA';
            break;
            case auth()->user()->role == 'Admin PO':
                $sect = 'PO';
            break;
            case auth()->user()->role == 'Admin MDF':
                $sect = 'MDF';
            break;
            case auth()->user()->role == 'Admin GA':
                $sect = 'GA';
            break;
            case auth()->user()->role == 'Admin ISS':
                $sect = 'ISS';
            break;
        } 
        if(auth()->user()->role == 'Administrator'){
            $lane = Lane::orderBy('no_lane','asc')->get();
            $barang = Barang::leftJoin('stock','stock.barang_id','=','barang.barang_id')->get();
        } else {
            $barang = Barang::leftJoin('stock','stock.barang_id','=','barang.barang_id')
                            ->where('barang.status_barang',$sect)
                            ->get();
            $lane = Lane::where('status_lane',$sect)->orderBy('no_lane','asc')->get();
        }
        $supplier = Supplier::select('supplier_id','supplier_name')->get();
        return view('transactions.transaction',compact('lane','barang','supplier'));
    }

    public function getTransaction(Request $request)
    {
        switch(true){
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 1:
                $sect = 'LA 1';
            break;
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 2:
                $sect = 'LA 2';
            break;
            case auth()->user()->role == 'Admin PP':
                $sect = 'PP';
            break;
            case auth()->user()->role == 'Admin EVA':
                $sect = 'EVA';
            break;
            case auth()->user()->role == 'Admin PO':
                $sect = 'PO';
            break;
            case auth()->user()->role == 'Admin MDF':
                $sect = 'MDF';
            break;
            case auth()->user()->role == 'Admin GA':
                $sect = 'GA';
            break;
            case auth()->user()->role == 'Admin ISS':
                $sect = 'ISS';
            break;
        } 
        if($request->input('start_date') && $request->input('end_date')){
            if($request->ajax()){
                $transaction = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
                                            ->select('transaction_id','barang.nama_barang as nama_barang','nik','namapic','section','no_lane','quantity','tanggal','status','transactions.factory','transactions.keterangan','barang.code_barang','barang.spek','barang.satuan','barang.status_barang','supplier_name')
                                            ->whereBetween('tanggal',[$request->start_date,$request->end_date]);
                                            if(auth()->user()->role == 'Administrator'){
                                                $transaction->orderBy('tanggal','DESC');
                                            } else {
                                                $transaction->where('status_barang',$sect)
                                                ->orderBy('tanggal','DESC');
                                            }
                return DataTables::of($transaction)
                        ->make(true);
            }
        } else {
            return DataTables::of([])->make(true);
        }
    }

    public function create(Request $request)
    {
        switch(auth()->user()->role){
            case 'Admin LA':
                $sect = 'LA';
            break;
            case 'Admin PP':
                $sect = 'PP';
            break;
            case 'Admin EVA':
                $sect = 'EVA';
            break;
            case 'Admin PO':
                $sect = 'PO';
            break;
            case 'Admin MDF':
                $sect = 'MDF';
            break;
            case 'Admin GA':
                $sect = 'GA';
            break;
            case 'Admin ISS':
                $sect = 'ISS';
            break;
        }
        $barang = Barang::where('code_barang',$request->code_barang)->first();
        $stock = Stock::where('barang_id',$barang->barang_id)
                    ->where('status_stock',$sect)
                    ->where('factory',auth()->user()->factory)
                    ->first();
        $opening = OpeningStock::where('barang_id',$barang->barang_id)
                                ->whereMonth('tanggal_opening',Carbon::parse($request->tanggal)->startOfMonth())
                                ->whereYear('tanggal_opening',$request->tanggal)
                                ->first();
        if($stock->stock === 0){
            return response()->json(['status'=>500,'message'=>'Stock not Available']);
        }
        if($stock->stock < $request->qty){
            return response()->json(['status'=>500,'message'=>'Stock not enough']);
        }
        if($stock){
            $stock->stock = $stock->stock - $request->qty;
            $stock->save();
            if(!$opening){
                OpeningStock::create([
                    'barang_id' => $barang->barang_id,
                    'tanggal_opening' => Carbon::now(),
                    'stock_opening' => $stock ? $stock->stock : 0,
                ]);
            }
            $transaction = Transaction::create([
                'barang_id' => $barang->barang_id,
                'nik' => $request->nik,
                'namapic' => $request->nama,
                'section' => $request->section,
                'no_lane' => '-',
                'quantity' => $request->qty,
                'tanggal' => $request->tanggal,
                'status' => 'OUT',
                'supplier_name' => $request->supplier_name,
                'factory' => auth()->user()->factory,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user()->name,
                'updated_by' => '',
            ]);
            if($stock->stock < $stock->min_stock){
                $data = [
                    'nama_barang' => $barang->nama_barang,
                    'spek' => $barang->spek,
                    'stock' => $stock->stock,
                    'min_stock' => $stock->min_stock
                ];
                Mail::to(auth()->user()->email)->send(new Notification($data));
            }
        } else {
            $stock = Stock::create([
                'barang_id' => $barang->barang_id,
                'stock' => $request->qty,
                'status_stock' => $sect,
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
                'nik' => $request->nik,
                'namapic' => $request->nama,
                'section' => $request->section,
                'no_lane' => '-',
                'quantity' => $request->qty,
                'tanggal' => $request->tanggal,
                'status' => 'OUT',
                'supplier_name' => $request->supplier_name,
                'factory' => auth()->user()->factory,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user()->name,
                'updated_by' => '',
            ]);
        }
        return response()->json(['status'=>201,'message'=>'Create transaction Success']);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);
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
        if($transaction){
            DB::beginTransaction();
            try{
                if($transaction->status == 'IN'){
                    $oldstock = Stock::where('barang_id',$transaction->barang_id)
                    ->where('status_stock',$status)
                    ->where('factory',auth()->user()->factory)
                    ->first();
                    $oldstock->stock -= $transaction->quantity;
                    $oldstock->save();

                    $transaction->nik = $request->nik;
                    $transaction->namapic = $request->nama;
                    $transaction->section = $request->section;
                    $transaction->no_lane = $request->no_lane;
                    $transaction->tanggal = $request->tanggal;
                    $transaction->quantity = $request->qty;
                    $transaction->factory = $request->factory;
                    $transaction->keterangan = $request->keterangan;
                    $transaction->updated_by = auth()->user()->name;
                    $transaction->save();

                    $newstock = Stock::where('barang_id',$transaction->barang_id)
                    ->where('status_stock',$status)
                    ->where('factory',auth()->user()->factory)
                    ->first();
                    $newstock->stock += $request->qty;
                    $newstock->save();    
                    // $recordNew = RecordTransaction::where('barang_id',$transaction->barang_id)->first();
                    // $recordNew->recordin = $request->qty;
                    // $recordNew->balance = $recordNew->recordstock + $request->qty - $recordNew->recordout;
                    // $recordNew->save();
                } else {
                    $oldstock = Stock::where('barang_id',$transaction->barang_id)
                    ->where('status_stock',$status)
                    ->where('factory',auth()->user()->factory)
                    ->first();
                    $oldstock->stock += $transaction->quantity;
                    $oldstock->save();
    
                    $transaction->nik = $request->nik;
                    $transaction->namapic = $request->nama;
                    $transaction->section = $request->section;
                    $transaction->no_lane = $request->no_lane;
                    $transaction->tanggal = $request->tanggal;
                    $transaction->quantity = $request->qty;
                    $transaction->factory = $request->factory;
                    $transaction->keterangan = $request->keterangan;
                    $transaction->updated_by = auth()->user()->name;
                    $transaction->save();
    
                    $newstock = Stock::where('barang_id',$transaction->barang_id)
                    ->where('status_stock',$status)
                    ->where('factory',auth()->user()->factory)
                    ->first();
                    $newstock->stock -= $request->qty;
                    $newstock->save();
                    // $recordNew = RecordTransaction::where('barang_id',$transaction->barang_id)->first();
                    // $recordNew->recordout = $request->qty;
                    // $recordNew->balance = $recordNew->recordstock + $recordNew->recordin - $request->qty;
                    // $recordNew->save();
                }
                DB::commit();
                return response()->json(['status'=>201,'message'=>'Update transaction success']);
            } catch(\Exception $e){
                DB::rollBack();
                return response()->json(['status'=>500,'message'=>'Update Transaction Faild'.$e->getMessage()]);
            }
        }
    }

    public function delete(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        if($transaction){
            DB::beginTransaction();
            try{
                if($transaction->status == 'OUT'){
                    $stock = Stock::where('barang_id',$transaction->barang_id)
                    ->where('factory',auth()->user()->factory)
                    ->first();
                    $stock->stock += $transaction->quantity;
                    $stock->save();
                    $transaction->delete();
                } else {
                    $stock = Stock::where('barang_id',$transaction->barang_id)
                    ->where('factory',auth()->user()->factory)
                    ->first();
                    $stock->stock -= $transaction->quantity;
                    $stock->save();
                    $transaction->delete();
                }
                DB::commit();
                return response()->json(['status'=>200,'message'=>'Delete transaction success']);
            } catch(\Exception $e){
                DB::rollBack();
                return response()->json(['status'=>500,'message'=>'Delete Transaction failed'.$e->getMessage()]);
            }
        }
    }

    public function SentEmail()
    {
        $data = [
            'nama_barang' => 'EGI',
            'spek' => 'Dewa',
            'stock' => 10,
            'min_stock' => 15
        ];
        Mail::to('fadli_azka_prayogi@stanley-electric.com')->send(new Notification($data));
        return "Email sent";
    }
}
