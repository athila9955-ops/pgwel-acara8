<?php

namespace App\Http\Controllers;

use App\Models\polylinesModel;
use Illuminate\Http\Request;

class PolylinesController extends Controller
{
    public function __construct()
    {
        $this->polylines = new polylinesModel();
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
                'geometry_polyline' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'geometry_polyline.required' => 'Field geometry polyline harus diisi.',
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
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => $request->geometry_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image'=> $name_image
        ];

        // Simpan data ke database
        if (!$this->polylines->create($data)) {
            return redirect()->route('peta')->with('error', 'Terjadi kesalahan saat menyimpan data.');
        };

        //kembali ke halaman peta setelah menyimpan data
        return redirect()->route('peta')->with('success', 'Polylines berhasil disimpan.');
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
            'title' => 'Edit Polyline',
            'id' => $id,
        ];

        return view('map-edit-polylines', $data);
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
                'geometry.required' => 'Field geometry polyline harus diisi.',
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

        // mencari nama file gambar berdasarkan ID polyline
        $image_old = $this->polylines->find($id)->image;

        //Get the uploaded image
        if ($request->hasFile('image')) {
        $image = $request->file('image');
        $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
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
        if (!$this->polylines->find($id)->update($data)) {
            return redirect()->route('peta')->with('error', 'Terjadi kesalahan saat memperbarui data polyline.');
        }
        ;

        //kembali ke halaman peta setelah menyimpan data
        return redirect()->route('peta')->with('success', 'Data polyline berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // mencari nama file gambar berdasarkan ID polyline
        $image = $this->polylines->find($id)->image;

       // Hapus dari data database
        if (!$this->polylines->destroy($id)) {
            return redirect()->route('peta')->with('error', 'Gagal menghapus data polylines.');
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

        //kembali ke halaman peta setelah menghapus data polyline
        return redirect()->route('peta')->with('success', 'Data Polyline berhasil dihapus.');
    }
}
