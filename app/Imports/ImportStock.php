<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\OpeningStock;
use App\Models\Stock;
use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportStock implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $barang = Barang::where('code_barang', $row['code_barang'])->first();

        $cekstock = Stock::where('barang_id', $barang->barang_id)->first();
        switch(true){
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 1:
                $sect = 'LA 1';
            break;
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 2:
                $sect = 'LA 2';
            break;
            case auth()->user()->role == 'Admin PP1':
                $sect = 'PP1';
            break;
            case auth()->user()->role == 'Admin PP2':
                $sect = 'PP2';
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
        if($sect == 'LA 1' || $sect == 'LA 2'){
            if ($cekstock) {
                $cekstock->update([
                    'stock' => $row['stock'],
                    'keterangan' => $row['keterangan']
                ]);
                Transaction::create([
                    'barang_id' => $barang->barang_id,
                    'nik' => auth()->user()->username,
                    'namapic' => auth()->user()->name,
                    'section' => auth()->user()->section,
                    'no_lane' => '-',
                    'quantity' => $row['stock'],
                    'tanggal' => Carbon::now(),
                    'status' => 'IN',
                    'factory' => auth()->user()->factory,
                    'keterangan' => $row['keterangan'],
                ]);
            } else {
                $cekstock = Stock::create([ 
                    'barang_id' => $barang->barang_id,
                    'stock' => $row['stock'],
                    'status_stock' => $sect,
                    'factory' => auth()->user()->factory,
                    'keterangan' => $row['keterangan']
                ]);
            }
        } else {
            if ($cekstock) {
                $cekstock->update([
                    'stock' => $cekstock->stock += $row['stock'],
                    'min_stock' => $row['min_stock'] == null || $row['min_stock'] == '' ? $cekstock->min_stock : $row['min_stock'],
                    'std_stock' => $row['std_stock'] == null || $row['std_stock'] == '' ? $cekstock->std_stock : $row['std_stock'],
                    'keterangan' => $row['keterangan'] == null || $row['keterangan'] == '' ? $cekstock->keterangan : $row['keterangan']
                ]);
                Transaction::create([
                    'barang_id' => $barang->barang_id,
                    'nik' => auth()->user()->username,
                    'namapic' => auth()->user()->name,
                    'section' => auth()->user()->section,
                    'no_lane' => '-',
                    'quantity' => $row['stock'],
                    'tanggal' => Carbon::now(),
                    'status' => 'IN',
                    'factory' => auth()->user()->factory,
                    'keterangan' => $row['keterangan'],
                ]);
            } else {
                $cekstock = Stock::create([ 
                    'barang_id' => $barang->barang_id,
                    'stock' => $row['stock'],
                    'min_stock' => $row['min_stock'],
                    'std_stock' => $row['std_stock'],
                    'status_stock' => $sect,
                    'factory' => auth()->user()->factory,
                    'keterangan' => $row['keterangan']
                ]);
            }
        
        $opening = OpeningStock::where('barang_id', $barang->barang_id)->first();

        if (!$opening) {
            OpeningStock::create([
                'barang_id' => $barang->barang_id,
                'tanggal_opening' => Carbon::now()->startOfMonth(),
                'stock_opening' => $cekstock ? $cekstock->stock : 0,
            ]);
        }

        return null;
        }
    }
}