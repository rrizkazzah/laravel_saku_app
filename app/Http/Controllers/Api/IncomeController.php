<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function createIncome(Request $request)
    {
    $request->validate([
        'wallet_id' => 'required|exists:wallet,id',
        'kategori_id' => 'required|exists:categories,id',
        'waktu' => 'required',
        'nominal' => 'required|numeric',
        'notes' => 'nullable',
    ]);

    $income = Income::create([
        'user_id' => $request->user()->id,
        'wallet_id' => $request->wallet_id,
        'kategori_id' => $request->kategori_id,
        'waktu' => $request->waktu,
        'notes' => $request->notes,
        'nominal' => $request->nominal,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Income berhasil ditambahkan',
        'data' => $income,
    ], 201);
}
}
