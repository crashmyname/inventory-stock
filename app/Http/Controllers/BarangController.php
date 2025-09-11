<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    //
    public function index(Request $request)
    {
        if($request->ajax()){
            switch(true){
                case auth()->user()->role == 'Administrator':
                    $barang = Barang::all();
                break;
                case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 1:
                    $barang = Barang::where('status_barang','LA 1')->orderBy('code_barang','ASC');
                break;
                case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 2:
                    $barang = Barang::where('status_barang','LA 2')->orderBy('code_barang','ASC');
                break;
                case auth()->user()->role == 'Admin PP':
                    $barang = Barang::where('status_barang','PP')->orderBy('code_barang','ASC');
                break;
                case auth()->user()->role == 'Admin EVA':
                    $barang = Barang::where('status_barang','EVA')->orderBy('code_barang','ASC');
                break;
                case auth()->user()->role == 'Admin PO':
                    $barang = Barang::where('status_barang','PO')->orderBy('code_barang','ASC');
                break;
                case auth()->user()->role == 'Admin MDF':
                    $barang = Barang::where('status_barang','MDF')->orderBy('code_barang','ASC');
                break;
                case auth()->user()->role == 'Admin GA':
                    $barang = Barang::where('status_barang','GA')->orderBy('code_barang','ASC');
                break;
                case auth()->user()->role == 'Admin ISS':
                    $barang = Barang::where('status_barang','ISS')->orderBy('code_barang','ASC');
                break;
            }
            return DataTables::of($barang)
                    ->make(true);
        }
        return view('barang.barang');
    }

    public function generateCode(Request $request)
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
        $barang = Barang::where('status_barang', $sect)->get();
        switch(true){
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 1:
                $code = 'LAM-'.str_pad($barang->count() + 1, 4, '0', STR_PAD_LEFT);
            break;
            case auth()->user()->role == 'Admin LA' && auth()->user()->factory == 2:
                $code = 'LA-'.str_pad($barang->count() + 1, 4, '0', STR_PAD_LEFT);
            break;
            case auth()->user()->role == 'Admin PP' && auth()->user()->factory == 1:
                $code = 'PP-'.str_pad($barang->count() + 1, 4, '0', STR_PAD_LEFT);
            break;
            case auth()->user()->role == 'Admin EVA' && auth()->user()->factory == 2:
                $code = 'EVA2-'.str_pad($barang->count() + 1, 4, '0', STR_PAD_LEFT);
            break;
            case auth()->user()->role == 'Admin PO' && auth()->user()->factory == 2:
                $code = 'PO2-'.str_pad($barang->count() + 1, 4, '0', STR_PAD_LEFT);
            break;
            case auth()->user()->role == 'Admin MDF':
                $code = 'MDF-'.str_pad($barang->count() + 1, 4, '0', STR_PAD_LEFT);
            break;
            case auth()->user()->role == 'Admin GA':
                $code = 'GA-'.str_pad($barang->count() + 1, 4, '0', STR_PAD_LEFT);
            break;
            case auth()->user()->role == 'Admin ISS':
                $code = 'ISS-'.str_pad($barang->count() + 1, 4, '0', STR_PAD_LEFT);
            break;
        }
        return response()->json(['status'=>200,'code' => $code]);
    }

    public function getBarang(Request $request)
    {
        $barang = Barang::leftJoin('stock','stock.barang_id','=','barang.barang_id')
                            ->select('nama_barang','spek','satuan','status_barang','stock.min_stock','stock.std_stock','stock.stock','code_barang')
                            ->where('code_barang',$request->code_barang)->first();
        return response()->json(['status'=>200,'data'=>$barang]);
    }

    public function create(Request $request)
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
        $validasi = $request->validate([
            'code_barang'=>'required',
            'nama_barang'=>'required',
            'satuan'=>'required',
            'spek'=>'required',
            'keterangan' => '',
        ]);
        $cekbarang = Barang::where('nama_barang',$validasi['nama_barang'])
                            ->where('spek',$validasi['spek'])
                            ->where('status_barang',$sect)
                            ->first();
        if($cekbarang){
            return response()->json(['status'=>406,'message'=>'Barang Sudah Ada']);
        }
        Barang::create([
            'code_barang' => $validasi['code_barang'],
            'nama_barang' => $validasi['nama_barang'],
            'satuan' => $validasi['satuan'],
            'spek' => $validasi['spek'],
            'status_barang' => $sect,
            'qrcode' => $validasi['code_barang'].'.png',
            'keterangan' => $validasi['keterangan']
        ]);

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
        $barcode = $validasi['code_barang'];
        $directory = base_path('../mart/barcode');
        $directory1 = base_path('../mart-service/public/barcode');

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        if (!is_dir($directory1)) {
            mkdir($directory1, 0777, true);
        }

        $filepath = $directory . '/' . $barcode.'.png';
        $filepath1 = $directory1 . '/' . $barcode.'.png';
        
        $imageData = $qrcode->render($barcode);
        file_put_contents($filepath, $imageData);
        file_put_contents($filepath1, $imageData);
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
            \Maatwebsite\Excel\Facades\Excel::import(new ImportBarang, $file);
            return response()->json(['status'=>201,'message'=>'Barang Imported Successfully']);
        } catch(\Exception $e){
            return response()->json(['status'=>500,'message'=>$e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);
        $barang->nama_barang = $request->nama_barang;
        $barang->satuan = $request->satuan;
        $barang->spek = $request->spek;
        $barang->keterangan = $request->keterangan;
        $barang->qrcode = $barang->code_barang.'.png';
        $options = new QROptions([
            'version'      => 5, // QR Code version (1-40, lebih besar = lebih banyak data)
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG, // Output sebagai PNG
            'eccLevel'     => QRCode::ECC_L, // Tingkat koreksi kesalahan
            'scale'        => 10, // Ukuran QR Code
            'imageBase64'  => false, // Jangan menggunakan base64
            'quietzoneSize'=> 4, // Ukuran margin (quiet zone) di sekitar QR Code
        ]);

        $qrcode = new QRCode($options);
        $barcode = $barang->code_barang;
        $directory = base_path('../mart/barcode');
        $directory1 = base_path('../mart-service/public/barcode');

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        if (!is_dir($directory1)) {
            mkdir($directory1, 0777, true);
        }

        $filepath = $directory . '/' . $barcode.'.png';
        $filepath1 = $directory1 . '/' . $barcode.'.png';
        
        $qrcode->render($barcode, $filepath);
        $qrcode->render($barcode, $filepath1);
        $barang->save();
        return response()->json(['status'=>201,'message'=>'Barang Updated Successfully']);
    }

    public function delete(Request $request, $id)
    {
        $barang = Barang::find($id);
        $barang->delete();
        return response()->json(['status'=>200,'message'=>'Barang Deleted Successfully']);
    }
}
