<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Debt;

class DebtController extends Controller
{
    function getDebt(Request $request) {
        try {
            $validateData = $this->validate($request, Debt::rules());
            $debt = Debt::where('user_id', $validateData['user_id'])
            ->where('state', false)
            ->orderBy('created_at', 'asc')->first();
            if (!$debt)
                return response()->json([
                    'message' => 'Resource not found into database'
                ], 400);
            return response()->json([
                'message' => 'OK',
                'debt' => $debt
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Failed to validate data',
                'error' => $e->validator->getMessageBag(),
            ], 422);
        }
    }
}
