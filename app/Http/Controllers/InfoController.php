<?php

namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Info::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemain' => 'required|string|max:255',
            'no_punggung' => 'required|string',
            'masa_kontrak' => 'required|string',
        ]);

        $info = Info::create($validated);
        return response()->json($info, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $info = Info::find($id);
        if (!$info) {
            return response()->json(['message' => 'Pemain not found'], 404);
        }
        return response()->json($info, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $info = Info::find($id);
        if (!$info) {
            return response()->json(['message' => 'Pemain not found'], 404);
        }

        $info->update($request->all());
        return response()->json($info, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $info = Info::find($id);
        if (!$info) {
            return response()->json(['message' => 'Pemain not found'], 404);
        }

        $info->delete();
        return response()->json(['message' => 'Penain deleted'], 200);
    }
}
