<?php

namespace App\Http\Controllers;

use App\Models\Lane;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LaneController extends Controller
{
    //
    public function index(Request $request)
    {
        if($request->ajax()){
            switch(true){
                case auth()->user()->role == 'Administrator':
                    $lane = Lane::all();
                break;
                case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 1:
                    $lane = Lane::where('status_lane','LA 1')->get();
                break;
                case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 2:
                    $lane = Lane::where('status_lane','LA 2')->get();
                break;
                case auth()->user()->role == 'Admin PP':
                    $lane = Lane::where('status_lane','PP')->get();
                break;
                case auth()->user()->role == 'Admin EVA':
                    $lane = Lane::where('status_lane','EVA')->get();
                break;
                case auth()->user()->role == 'Admin PO':
                    $lane = Lane::where('status_lane','PO')->get();
                break;
            }
            return DataTables::of($lane)
            ->make(true);
        }
        return view('lane.lane');
    }

    public function create(Request $request)
    {
        $validasi = $request->validate([
            'no_lane'=>'required',
            'keterangan'=>'',
        ]);
        switch(true){
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 1:
                $status = 'LA 1';
            break;
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 2:
                $status = 'LA 2';
            break;
            case auth()->user()->role == 'Admin PP':
                $status = 'PP';
            break;
            case auth()->user()->role == 'Admin EVA':
                $status = 'EVA';
            break;
            case auth()->user()->role == 'Admin PO':
                $status = 'PO';
            break;
        }
        Lane::create([
            'no_lane' => $validasi['no_lane'],
            'status_lane' => $status,
            'keterangan' => $validasi['keterangan']
        ]);
        return response()->json(['status'=>201,'message'=>'Create Lane Success']);
    }

    public function update(Request $request, $id)
    {
        $lane = Lane::find($id);
        $lane->no_lane = $request->no_lane;
        $lane->keterangan = $request->keterangan;
        $lane->save();
        return response()->json(['status'=>201,'message'=>'Update Lane Success']);
    }

    public function delete(Request $request, $id)
    {
        $lane = Lane::find($id);
        $lane->delete();
        return response()->json(['status'=>200,'message'=>'Delete Lane Success']);
    }
}
