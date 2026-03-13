<?php

namespace App\Imports;

use App\Models\Barang;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportBarang implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $user = auth()->user();
        switch (true) {
            case $user->role == 'Admin LA' && $user->factory == 1:
                $sect = 'LA 1';
                break;
            case $user->role == 'Admin LA' && $user->factory == 2:
                $sect = 'LA 2';
                break;
            case $user->role == 'Admin PP1':
                $sect = 'PP1';
                break;
            case $user->role == 'Admin PP2':
                $sect = 'PP2';
                break;
            case $user->role == 'Admin MDF':
                $sect = 'MDF';
                break;
            case $user->role == 'Admin GA':
                $sect = 'GA';
                break;
            case $user->role == 'Admin ISS':
                $sect = 'ISS';
                break;
            default:
                $sect = null;
        }

        if (!$sect) {
            return null;
        }

        $existingBarang = Barang::where('nama_barang', $row['nama_barang'])
                        ->where('spek', $row['spek'])
                        ->where('status_barang',$sect)
                        ->first();

        if ($existingBarang) {
            $existingBarang->update([
                'nama_barang' => $row['nama_barang'],
                'spek' => $row['spek'],
                'satuan' => $row['satuan'],
                'status_barang' => $sect,
                'qrcode' => $existingBarang->code_barang.'.png',
                'keterangan' => $row['keterangan']
            ]);
            $this->generateQRCode($existingBarang->code_barang);
            return null; 
        } else {
            $code_barang = $this->generateCode($sect);
            $this->generateQRCode($code_barang);
            return new Barang([
                'code_barang' => $code_barang,
                'nama_barang' => $row['nama_barang'],
                'spek' => $row['spek'],
                'satuan' => $row['satuan'],
                'status_barang' => $sect,
                'qrcode' => $code_barang.'.png',
                'keterangan' => $row['keterangan']
            ]);
        }
    }

    private function generateCode($sect)
    {
        $barangCount = Barang::where('status_barang', $sect)->count() + 1;

        switch ($sect) {
            case 'LA 1':
                $prefix = 'LAM-';
                break;
            case 'LA 2':
                $prefix = 'LA-';
                break;
            case 'PP1':
                $prefix = 'PP1-';
                break;
            case 'PP2':
                $prefix = 'PP2-';
                break;
            case 'MDF':
                $prefix = 'MDF-';
                break;
            case 'GA':
                $prefix = 'GA-';
                break;
            case 'ISS':
                $prefix = 'ISS-';
                break;
            default:
                $prefix = 'UNK-'; // Unknown prefix
        }

        return $prefix . str_pad($barangCount, 4, '0', STR_PAD_LEFT);
    }

    private function generateQRCode($barang)
    {
        $options = new QROptions([
            'version'      => 5, // QR Code version (1-40, lebih besar = lebih banyak data)
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG, // Output sebagai PNG
            'eccLevel'     => QRCode::ECC_L, // Tingkat koreksi kesalahan
            'scale'        => 10, // Ukuran QR Code
            'imageBase64'  => false, // Jangan menggunakan base64
            'quietzoneSize'=> 4, // Ukuran margin (quiet zone) di sekitar QR Code
        ]);

        // Buat objek QRCode dengan opsi
        $qrcode = new QRCode($options);
        $directory = base_path('../mart/barcode');
        $directory1 = base_path('../mart-service/public/barcode');

        // Cek apakah direktori sudah ada, jika belum buat
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        if (!is_dir($directory1)) {
            mkdir($directory1, 0777, true);
        }
        $filepath = $directory . '/' . $barang . '.png';
        $filepath1 = $directory1 . '/' . $barang . '.png';
        $imageData = $qrcode->render($barang);
        file_put_contents($filepath, $imageData);
        file_put_contents($filepath1, $imageData);
    }
}
