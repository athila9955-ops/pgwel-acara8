@extends('layouts.template')

@section('')
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Aplikasi Geospasial CRUD</h3>
            </div>
            <div class="card-body">
                <p>
                    Aplikasi ini dibuat untuk memenuhi tugas mata kuliah Pratikum Pemrograman Web Lanjut.
                    Aplikasi ini menampilkan peta interaktif yang menunjukkan objek dengan geometri titik,
                    garis, dan area yang ditambah, ditampilkan, dan diubah, dan dihapus. Aplikasi ini dikembangkan
                    dengan menggunakan Laravel dan PostgreSQL - PostGIS.
                </p>
                <p>
                    Aplikasi ini dikembangkan oleh Athila, mahasiswa semester 4 Program Studi Sistem Informasi Geografis
                    dengan dibantu oleh Dosen Mata Kuliah.
                    Pengembangan aplikasi ini merupakan bagian dari penerapan ilmu yang diperoleh dalam mata kuliah
                    Pratikum Pemrograman Web Lanjut, dengan tujuan mengintegrasikan teknologi web modern bersama
                    sistem informasi geografis untuk menghasilkan solusi pemetaan yang interaktif dan fungsional.
                </p>

            </div>
        </div>

        <div class="row mt-3">
            <div class="col-3">
                <div class="card border-primary">
                    <div class="card-header">
                            <h3>Jumlah Point</h3>
                    </div>
                    <div class="card-body text-center">
                        <h1>
                            {{ $points_count }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-success">
                    <div class="card-header">
                            <h3>Jumlah Polyline</h3>
                    </div>
                    <div class="card-body text-center">
                        <h1>
                            {{ $polylines_count }}
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-dark">
                    <div class="card-header">
                            <h3>Jumlah Polygon</h3>
                    </div>
                    <div class="card-body text-center">
                        <h1>
                            {{ $polygon_count }}
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card border-warning">
                    <div class="card-header">
                            <h3>Jumlah User</h3>
                    </div>
                    <div class="card-body text-center">
                        <h1>
                            {{ $user_count }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
