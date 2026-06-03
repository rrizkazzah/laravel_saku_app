<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Nominal_wallet;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function createWallet(Request $request)
    {
        $request->validate([
            'nama_wallet' => 'required',
             'nominal' => 'required|numeric|min:0',
        ]);

        $wallet = Wallet::create([
            'user_id' => $request->user()->id,
            'nama_wallet' => $request->nama_wallet,
        ]);

        Nominal_wallet::create([
        'user_id' => $request->user()->id,
        'wallet_id' => $wallet->id,
        'nominal' => $request->nominal,
]);

        return response()->json([
            'status' => 'success',
            'data' => $wallet,
        ], 201);
    }
}
