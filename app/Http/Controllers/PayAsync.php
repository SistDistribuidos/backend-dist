<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayAsync extends Controller
{
    function payAsync(Request $request){
        
         // callback,idTransaccion, monto,user.
        $inputs = $request->all();
        async_function::dispatch($inputs);
        return response()->json([
            'message' => 'Su solicitud fue recibida Con exito'
        ], $code);
       
        
    }



    function realizarTransaccion($parametros){

    }
}
