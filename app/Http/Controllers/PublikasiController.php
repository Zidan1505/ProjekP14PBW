<?php

namespace App\Http\Controllers;

use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Wajib di-import untuk mengelola file

class PublikasiController extends Controller
{
    /**
     * Menampilkan semua data publikasi, diurutkan dari yang terbaru.
     */
    public function index()
    {
        // Mengambil semua publikasi dan mengurutkannya berdasarkan tanggal rilis terbaru
        $publikasi = Publikasi::latest('release_date')->get();
        return response()->json($publikasi);
    }

    /**
     * Menampilkan satu data publikasi berdasarkan ID.
     */
    public function show($id)
    {
        $publikasi = Publikasi::findOrFail($id);
        return response()->json($publikasi);
    }

    /**
     * Menyimpan data publikasi baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi request (gunakan snake_case agar konsisten)
        $validatedData = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'release_date' => 'required|date',
            'cover_file'   => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi file
        ]);

        // 2. Handle file upload
        $coverPath = $request->file('cover_file')->store('covers', 'public');

        // 3. Simpan data ke database
        $publikasi = Publikasi::create([
            'title'        => $validatedData['title'],
            'description'  => $validatedData['description'],
            'release_date' => $validatedData['release_date'],
            'cover_url'    => $coverPath, // Simpan path file, bukan URL lengkap
        ]);

        // 4. Kembalikan respons sukses dengan data yang baru dibuat
        return response()->json($publikasi, 201);
    }

    /**
     * Mengupdate data publikasi yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $publikasi = Publikasi::findOrFail($id);

        $validatedData = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'release_date' => 'required|date',
            'cover_file'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // File tidak wajib di-update
        ]);
        
        $dataToUpdate = [
            'title'        => $validatedData['title'],
            'description'  => $validatedData['description'],
            'release_date' => $validatedData['release_date'],
        ];

        // Jika ada file sampul baru yang di-upload
        if ($request->hasFile('cover_file')) {
            // Hapus file lama jika ada
            if ($publikasi->cover_url) {
                Storage::disk('public')->delete($publikasi->cover_url);
            }
            // Simpan file baru dan update path-nya
            $dataToUpdate['cover_url'] = $request->file('cover_file')->store('covers', 'public');
        }

        $publikasi->update($dataToUpdate);

        return response()->json($publikasi);
    }

    /**
     * Menghapus data publikasi.
     */
    public function destroy($id)
    {
        $publikasi = Publikasi::findOrFail($id);

        // Hapus file sampul dari storage jika ada
        if ($publikasi->cover_url) {
            Storage::disk('public')->delete($publikasi->cover_url);
        }

        // Hapus data dari database
        $publikasi->delete();

        return response()->json(['message' => 'Publikasi berhasil dihapus.']);
    }
}