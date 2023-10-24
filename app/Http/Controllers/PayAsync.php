<?php

namespace App\Http\Controllers;

use App\Jobs\async_function;
use Illuminate\Http\Request;

class PayAsync extends Controller
{
    function payAsync(Request $request){
        
         // callback,idTransaccion, monto,user.
        $inputs = $request->all();
        async_function::dispatch($inputs);
        return response()->json([
            'message' => 'Su solicitud fue recibida Con exito'
        ], 200);
       
        
    }



    function realizarTransaccion($parametros){

    }
}
