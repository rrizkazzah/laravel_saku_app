<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Beri_pinjaman;
use Illuminate\Http\Request;

class BeriPinjamanController extends Controller
{
    public function createBeriPinjaman (Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:wallet,id',
            'waktu' => 'required',
            'nama' => 'required',
            'notes' => 'nullable',
            'nominal' => 'required|numeric',
        ]);

        $beriPinjaman = Beri_pinjaman::create([
            'user_id' => $request->user()->id,
            'wallet_id' => $request->wallet_id,
            'waktu' => $request->waktu,
            'nama' => $request->nama,
            'notes' => $request->notes,
            'nominal' => $request->nominal,
            'status' => 'unpaid',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Beri pinjaman berhasil ditambahkan',
            'data' => $beriPinjaman,
        ], 201);
    }

    public function updateStatusBeriPinjaman(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:paid,unpaid',
        ]);

        $beriPinjaman = Beri_pinjaman::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $beriPinjaman->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status hutang berhasil diupdate',
            'data' => $beriPinjaman,
        ]);
    }
}
