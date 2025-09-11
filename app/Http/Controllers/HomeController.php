<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Lane;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $user = User::count();
        switch(true){
            case auth()->user()->role == 'Administrator':
                $barang = Barang::count();
                $lane = Lane::count();
                $transaction = Transaction::count();
            break;
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 1:
                $barang = Barang::where('status_barang','LA 1')->count();
                $lane = Lane::where('status_lane','LA 1')->count();
                $transaction = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
                                            ->where('status_barang','LA 1')
                                            ->count();
            break;
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 2:
                $barang = Barang::where('status_barang','LA 2')->count();
                $lane = Lane::where('status_lane','LA 2')->count();
                $transaction = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
                                            ->where('status_barang','LA 2')
                                            ->count();
            break;
            case auth()->user()->role == 'Admin PP':
                $barang = Barang::where('status_barang','PP')->count();
                $lane = Lane::where('status_lane','PP')->count();
                $transaction = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
                                            ->where('status_barang','PP')
                                            ->count();
            break;
            case auth()->user()->role == 'Admin EVA':
                $barang = Barang::where('status_barang','EVA')->count();
                $lane = Lane::where('status_lane','EVA')->count();
                $transaction = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
                                            ->where('status_barang','EVA')
                                            ->count();
            break;
            case auth()->user()->role == 'Admin PO':
                $barang = Barang::where('status_barang','PO')->count();
                $lane = Lane::where('status_lane','PO')->count();
                $transaction = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
                                            ->where('status_barang','PO')
                                            ->count();
            break;
            case auth()->user()->role == 'Admin MDF':
                $barang = Barang::where('status_barang','MDF')->count();
                $lane = Lane::where('status_lane','MDF')->count();
                $transaction = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
                                            ->where('status_barang','MDF')
                                            ->count();
            break;
            case auth()->user()->role == 'Admin GA':
                $barang = Barang::where('status_barang','GA')->count();
                $lane = Lane::where('status_lane','GA')->count();
                $transaction = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
                                            ->where('status_barang','GA')
                                            ->count();
            break;
            case auth()->user()->role == 'Admin ISS':
                $barang = Barang::where('status_barang','ISS')->count();
                $lane = Lane::where('status_lane','ISS')->count();
                $transaction = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
                                            ->where('status_barang','ISS')
                                            ->count();
            break;
        }
        return view('home.home',compact('user','barang','lane','transaction'));
    }

    public function countChart(Request $request)
    {
        $monthlyTransactions = [
            'Jan' => 0,
            'Feb' => 0,
            'Mar' => 0,
            'Apr' => 0,
            'Mei' => 0,
            'Jun' => 0,
            'Jul' => 0,
            'Aug' => 0,
            'Sep' => 0,
            'Oct' => 0,
            'Nov' => 0,
            'Dec' => 0,
        ];
        $query = Transaction::leftJoin('barang','barang.barang_id','=','transactions.barang_id')
            ->selectRaw('MONTH(tanggal) as month, COUNT(*) as count')
            ->whereYear('tanggal', Carbon::now()->year)
            ->groupByRaw('MONTH(tanggal)');
        switch (true) {
            case auth()->user()->role == 'Administrator':
                break;
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 1:
                $query->where('status_barang', 'LA 1');
                break;
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 2:
                $query->where('status_barang', 'LA 2');
                break;
            case auth()->user()->role == 'Admin PP':
                $query->where('status_barang', 'PP');
                break;
            case auth()->user()->role == 'Admin EVA':
                $query->where('status_barang', 'EVA');
                break;
            case auth()->user()->role == 'Admin PO':
                $query->where('status_barang', 'PO');
                break;
            case auth()->user()->role == 'Admin MDF':
                $query->where('status_barang', 'MDF');
                break;
            case auth()->user()->role == 'Admin GA':
                $query->where('status_barang', 'GA');
                break;
            case auth()->user()->role == 'Admin ISS':
                $query->where('status_barang', 'ISS');
                break;
            default:
                break;
        }

        $results = $query->get();

        // Map hasil query ke dalam array monthlyTransactions
        foreach ($results as $result) {
            $monthName = Carbon::createFromFormat('m', $result->month)->format('M');
            $monthlyTransactions[$monthName] = $result->count;
        }

        return response()->json($monthlyTransactions);
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
