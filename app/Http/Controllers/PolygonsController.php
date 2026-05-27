<?php

namespace App\Http\Controllers;

use App\Models\polygonsModel;
use Illuminate\Http\Request;

class PolygonsController extends Controller
{
    // Fungsi untuk mengkoneksikan model ke controller
    public function __construct()
    {
        $this->polygon = new polygonsModel();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validasi Input
        $request->validate(
            [
                'geometry_polygon' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'geometry_polygon.required' => 'Field geometry polygon harus diisi.',
                'name.required' => 'Field name harus diisi.',
                'name.string' => 'Field name harus berupa string.',
                'name.max' => 'Field name tidak boleh lebih dari 255 karakter.',
                'description.required' => 'Field description harus diisi.',
                'description.string' => 'Field description harus berupa string.',
                'image.image' => 'Field image harus berupa gambar.',
                'image.mimes' => 'Field image harus berformat jpeg,png, atau jpg.',
                'image.max' => 'Field image tidak boleh lebih dari 2MB.',
            ]
        );

        //Create directory for images if it doesn't exist
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        //Get the uploaded image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => $request->geometry_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image'=> $name_image
        ];

        // Simpan data ke database
        if (!$this->polygon->create($data)) {
            return redirect()->route('peta')->with('error', 'Terjadi kesalahan saat menyimpan data.');
        };

        //kembali ke halaman peta setelah menyimpan data
        return redirect()->route('peta')->with('success', 'Polygon berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Polygon',
            'id' => $id,
        ];

        return view('map-edit-polygons', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi Input
        $request->validate(
            [
                'geometry'=> 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'geometry.required' => 'Field geometry polygon harus diisi.',
                'name.required' => 'Field name harus diisi.',
                'name.string' => 'Field name harus berupa string.',
                'name.max' => 'Field name tidak boleh lebih dari 255 karakter.',
                'description.required' => 'Field description harus diisi.',
                'description.string' => 'Field description harus berupa string.',
                'image.image' => 'Field image harus berupa gambar.',
                'image.mimes' => 'Field image harus berformat jpeg,png, atau jpg.',
                'image.max' => 'Field image tidak boleh lebih dari 2MB.',
            ]
        );

        //Create directory for images if it doesn't exist
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // mencari nama file gambar berdasarkan ID polygon
        $image_old = $this->polygon->find($id)->image;

        //Get the uploaded image
        if ($request->hasFile('image')) {
        $image = $request->file('image');
        $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
        $image->move('storage/images', $name_image);

        // Hapus file gambar jika ada
        if ($image_old != null) {
            // cek apakah file gambar ada sebelum menghapus
            if (file_exists('storage/images/' . $image_old)) {
            // hapus file gambar
                unlink('storage/images/' . $image_old);
            }
        }

        }else {
            $name_image = $image_old;
        }

        $data = [
            'geom' => $request->geometry,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Simpan data ke database
        if (!$this->polygon->find($id)->update($data)) {
            return redirect()->route('peta')->with('error', 'Terjadi kesalahan saat memperbarui data polygon.');
        }
        ;

        //kembali ke halaman peta setelah menyimpan data
        return redirect()->route('peta')->with('success', 'Data polygon berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // mencari nama file gambar berdasarkan ID polygon
        $image = $this->polygon->find($id)->image;

       // Hapus dari data database
        if (!$this->polygon->destroy($id)) {
            return redirect()->route('peta')->with('error', 'Gagal menghapus data polygon.');
        }
        ;

        // Hapus file gambar jika ada
        if ($image != null) {
            // cek apakah file gambar ada sebelum menghapus
            if (file_exists('storage/images/' . $image)) {
            // hapus file gambar
                unlink('storage/images/' . $image);
            }
        }

        //kembali ke halaman peta setelah menghapus data polygon
        return redirect()->route('peta')->with('success', 'Data Polygon berhasil dihapus.');
    }
}
