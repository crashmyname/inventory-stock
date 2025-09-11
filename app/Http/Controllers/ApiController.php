<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    //
    public function ApiData(Request $request)
    {
        $emp = $request->nik;
        $key = 'P@55W0RD';
        try{
            $api = Http::get('http://10.203.68.47:90/fambook/config/newapi.php?action=getEmployeeByNIK&nik='.$emp.'&api_key='.$key);
            $result = $api->json();
            if($result !== null)
            {
                return response()->json(['status'=>200,'data'=>$result]);
            }
        } catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
