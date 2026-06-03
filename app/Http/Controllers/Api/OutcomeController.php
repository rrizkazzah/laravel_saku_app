<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outcome;
use App\Models\Nominal_wallet;
use Illuminate\Http\Request;

class OutcomeController extends Controller
{
    public function createOutcome(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:wallet,id',
            'kategori_id' => 'required|exists:categories,id',
            'waktu' => 'required',
            'nominal' => 'required|numeric',
            'notes' => 'nullable',
        ]);

        $saldo = Nominal_wallet::where(
            'wallet_id',
            $request->wallet_id
        )->first();

        if (!$saldo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Saldo wallet tidak ditemukan'
            ], 404);
        }

        if ($saldo->nominal < $request->nominal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Saldo tidak mencukupi'
            ], 400);
        }

        $outcome = Outcome::create([
            'user_id' => $request->user()->id,
            'wallet_id' => $request->wallet_id,
            'kategori_id' => $request->kategori_id,
            'waktu' => $request->waktu,
            'notes' => $request->notes,
            'nominal' => $request->nominal,
        ]);

        $saldo->decrement('nominal', $request->nominal);

        return response()->json([
            'status' => 'success',
            'message' => 'Outcome berhasil ditambahkan',
            'data' => $outcome,
        ], 201);
    }
}
