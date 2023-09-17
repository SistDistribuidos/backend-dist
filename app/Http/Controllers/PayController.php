<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Pay;
use App\Models\Debt;

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

            if ($validateData['amount'] > $debt->amount)
              return $this->handleException('request cannot be made, the payment exceeds the debt', 422);

            try {
                $amount = $validateData['amount'];
                $debt->amount = $debt->amount - $amount;
                if ($debt->amount == 0)
                  $debt->state = true;
                $debt->save();

                $pay = new Pay();
                $pay->amount = $validateData['amount'];
                $pay->debt_id = $validateData['debt_id'];
                $pay->save();

                return response()->json([
                    'message' => 'payment successfully registered',
                    'payment' => $pay,
                    'debt' =>$debt
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
