<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportSupplier implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $ceksupplier = Supplier::where('supplier_name',$row['supplier_name'])
                            ->first();
        if($ceksupplier){
            $ceksupplier->update([
                'supplier_name' => $row['supplier_name'],
                'keterangan' => $row['keterangan']
            ]);
        } else {
            return new Supplier([
                'supplier_name' => $row['supplier_name'],
                'keterangan' => $row['keterangan']
            ]);
        }
    }
}
