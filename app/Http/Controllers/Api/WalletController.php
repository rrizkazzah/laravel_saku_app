<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function createWallet(Request $request)
    {
        $request->validate([
            'nama_wallet' => 'required',
        ]);

        $wallet = Wallet::create([
            'user_id' => $request->user()->id,
            'nama_wallet' => $request->nama_wallet,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $wallet,
        ], 201);
    }
}
