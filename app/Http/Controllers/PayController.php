<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Pay;
use App\Models\Debt;
use Illuminate\Support\Facades\DB;

class PayController extends Controller
{
    public function payDebt(Request $request) {
        try {
            $validateData = $this->validate($request, Pay::rules());
            $debt = Debt::where('id', $validateData['debt_id'])->first();

            if (!$debt)
              return $this->handleException('Debt not found', 400);

            if ($debt->state)
              return $this->handleException('request cannot be made, debt paid', 422);
            
            if ( $debt->amount_paid + $validateData['amount'] > $debt->amount )
              return $this->handleException('request cannot be made, the payment exceeds the debt', 422);
            try {
                // Llamada al procedimiento almacenado
                $result = DB::select('SELECT * FROM update_debt(?, ?, ?)', [$validateData['debt_id'], $validateData['amount'], $debt->amount_paid + $validateData['amount'] == $debt->amount]);

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

    public function handleException($message, $code) {
        return response()->json([
            'message' => $message
        ], $code);
    }
}
