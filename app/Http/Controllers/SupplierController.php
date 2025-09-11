<?php

namespace App\Http\Controllers;

use App\Imports\ImportSupplier;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    //
    public function index(Request $request)
    {
        if($request->ajax()){
            $supplier = Supplier::all();
            return DataTables::of($supplier)
                    ->make(true);
        }
        return view('suppliers.supplier');
    }

    public function create(Request $request)
    {
        $validasi = $request->validate([
            'supplier_name'=>'required',
        ]);
        $cekbarang = Supplier::where('supplier_name',$validasi['supplier_name'])
                            ->first();
        if($cekbarang){
            return response()->json(['status'=>406,'message'=>'Barang Sudah Ada']);
        }
        Supplier::create([
            'supplier_name' => $validasi['supplier_name'],
            'keterangan' => $request->keterangan,
        ]);
        return response()->json(['status'=>201,'message'=>'Barang Created Successfully']);
    }

    public function Import(Request $request)
    {
        $validasi = $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
        $file = $request->file('file');
        $filename = time().'.'.$file->getClientOriginalExtension();
        try{
            \Maatwebsite\Excel\Facades\Excel::import(new ImportSupplier, $file);
            return response()->json(['status'=>201,'message'=>'Supplier Imported Successfully']);
        } catch(\Exception $e){
            return response()->json(['status'=>500,'message'=>$e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        $supplier->supplier_name = $request->supplier_name;
        $supplier->keterangan = $request->keterangan;
        $supplier->save();
        return response()->json(['status'=>201,'message'=>'Supplier Updated Successfully']);
    }

    public function delete(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();
        return response()->json(['status'=>200,'message'=>'Supplier Deleted Successfully']);
    }
}
