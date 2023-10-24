<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Pay;
use App\Models\Debt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

// $response = Http::post('https://api.example.com/endpoint', [
//     'key1' => 'value1',
//     'key2' => 'value2',
//     // Agrega los datos que deseas enviar en la solicitud POST aquí.
// ]);


class PayController extends Controller
{
    public function payDebt(Request $request) {
        try {
            $validateData = $this->validate($request, Pay::rules());
            $debt = Debt::where('id', $validateData['debt_id'])->first();

            if (!$debt)
              return $this->handleException('Debt not found', 400);

            if ($debt->state){
                return $this->handleException('request cannot be made, debt paid', 422);
            }
            
            $amount =  $debt->amount_paid + $validateData['amount'];
            $pago =  $request['amount'];
            $difference = $amount - $debt->amount;

            if ( $amount > $debt->amount && $difference >= 1 ){
                return $this->handleException('request cannot be made, the payment exceeds the debt', 422);
            }
              
            try {
                // Llamada al procedimiento almacenado
                $result = DB::select('SELECT * FROM update_debt(?, ?, ?)', [$validateData['debt_id'], $validateData['amount'], $difference >= 0]);

                return response()->json([
                    'message' => 'payment successfully registered',
                    'payment' => $validateData['amount'],
                    'debt' => $result[0]
                ],201);
            } catch (QueryException $e) {
                return $this->handleException('Failed to execute query', 500);
            }
            
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Failed to validate data',
                'error' => $e->validator->getMessageBag(),
            ], 422);
        }
    }

    public function payDebt2($request) {
        try {
           // $validateData = $this->validate($request, Pay::rules());
            $debt = Debt::where('id', $request['debt_id'])->first();

            if (!$debt)
              Http::post($request->callaback, [
                'mensaje' => 'Ocurrio un error en la solicitud',
                // Agrega los datos que deseas enviar en la solicitud POST aquí.
            ]);
            return;

            if ($debt->state){
                Http::post($request->callaback, [
                    'mensaje' => 'Ocurrio un error en la solicitud',
                    // Agrega los datos que deseas enviar en la solicitud POST aquí.
                ]);
                return;
            }
            
            $amount =  $debt->amount_paid + $request['amount'];
            $pago =  $request['amount'];
            $difference = $amount - $debt->amount;

            if ( $amount > $debt->amount && $difference >= 1 ){
                Http::post($request->callaback, [
                    'mensaje' => 'Datos erroneos Excediste la deuda',
                    // Agrega los datos que deseas enviar en la solicitud POST aquí.
                ]);
                return;
            }
              
            try {
                // Llamada al procedimiento almacenado
                $result = DB::select('SELECT * FROM update_debt(?, ?, ?)', [$request['debt_id'], $request['amount'], $difference >= 0]);

               Http::post($request->callaback, [
                    'mensaje' => 'Trasaccion realizada con exito',
                    // Agrega los datos que deseas enviar en la solicitud POST aquí.
                ]);
            
            } catch (QueryException $e) {
                Http::post($request->callaback, [
                    'mensaje' => 'Ocurrio un error en la solicitud',
                    // Agrega los datos que deseas enviar en la solicitud POST aquí.
                ]);
            }
            
        } catch (ValidationException $e) {
            Http::post($request->callaback, [
                'mensaje' => 'Ocurrio un erro en el servidor',
                // Agrega los datos que deseas enviar en la solicitud POST aquí.
            ]);
            return;
        }
    }

    public function handleException($message, $code) {
        return response()->json([
            'message' => $message
        ], $code);
    }
}
