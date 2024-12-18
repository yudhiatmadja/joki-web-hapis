<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Image::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $image = Image::create([
                'title' => $request->title,
                'image_path' => $path,
            ]);

            return response()->json($image, 201);
        }

        return response()->json(['error' => 'File not uploaded'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        $image = Image::find($id);
        if ($image) {
            return response()->json($image, 200);
        }
        return response()->json(['error' => 'Image not found'], 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Image $image)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Log untuk debugging
        \Log::info($request->all());

        // Validasi
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255', // Bisa berupa string atau path
        ]);

        // Cari data berdasarkan ID
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Update title jika ada
        if ($request->filled('title')) {
            $image->title = $request->title;
        }

        // Update image_path
        if ($request->filled('image')) {
            $image->image_path = $request->image; // Memperbarui path
        }

        // Simpan perubahan
        $image->save();

        return response()->json($image, 200);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        $image = Image::find($id);
        if ($image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
            return response()->json(['message' => 'Image deleted'], 200);
        }
        return response()->json(['error' => 'Image not found'], 404);
    }
}
