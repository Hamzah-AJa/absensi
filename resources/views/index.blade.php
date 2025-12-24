@extends('layout.main')

@section('title')
    <title>Absensi | Dashboard</title>
@endsection

@section('content')
    <div class="container">
        <h4 class="mr-4 d-none d-lg-inline text-black bold">Welcome, {{ Auth()->user()->nama }}</h4>
        <div class="card mt-4">
            <div class="card-body">
                @php
                    $totalHadir = 0;
                    $totalIjin = 0;
                    $totalSakit = 0;
                    $totalAlfa = 0;

                    foreach ($absens as $a) {
                        if ($a->keterangan == 'Hadir') {
                            $totalHadir++;
                        } elseif ($a->keterangan == 'Ijin') {
                            $totalIjin++;
                        } elseif ($a->keterangan == 'Sakit') {
                            $totalSakit++;
                        } else {
                            $totalAlfa++;
                        }
                    }
                @endphp
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card card-stat bg-success text-white shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">Hadir</h6>
                                <h2 class="count" data-target="{{ $totalHadir }}">0</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stat bg-primary text-white shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">Ijin</h6>
                                <h2 class="count" data-target="{{ $totalIjin }}">0</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stat bg-warning text-dark shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">Sakit</h6>
                                <h2 class="count" data-target="{{ $totalSakit }}">0</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stat bg-danger text-white shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">Alfa</h6>
                                <h2 class="count" data-target="{{ $totalAlfa }}">0</h2>
                            </div>
                        </div>
                    </div>
                </div>



                {{-- Absen Hari Ini --}}
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4>Absen Hari Ini</h4>
                            <a href="/absen" class="btn text-white btn-success mr-4">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($absennow as $a)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $a->tanggal->isoformat('D/M/YYYY') }}</td>
                                        {{-- aman jika relasi siswa null --}}
                                        <td>{{ optional($a->siswa)->nama ?? '-' }}</td>
                                        <td>
                                            @if ($a->keterangan == 'Sakit')
                                                <span class="badge bg-warning">{{ $a->keterangan }}</span>
                                            @elseif ($a->keterangan == 'Hadir')
                                                <span class="badge bg-success">{{ $a->keterangan }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $a->keterangan }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="/absen/{{ $a->id_absensi }}/edit" class="btn btn-warning text-white">
                                                <i class="fa fa-pen" aria-hidden="true"></i>
                                            </a>
                                            <a href="/absen/{{ $a->id_absensi }}/delete"
                                                onclick="return confirm('Apakah kamu yakin ingin menghapus data?')"
                                                class="ms-2 btn btn-danger text-white">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Absen Total --}}
                <div class="card" style="margin-top: 50px">
                    <div class="card-header">
                        <h4>Absen Total</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="myTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($absens as $a)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $a->tanggal->isoformat('D/M/YYYY') }}</td>
                                        {{-- aman jika relasi siswa null --}}
                                        <td>{{ optional($a->siswa)->nama ?? '-' }}</td>
                                        <td>
                                            @if ($a->keterangan == 'Sakit')
                                                <span class="badge bg-warning">{{ $a->keterangan }}</span>
                                            @elseif ($a->keterangan == 'Hadir')
                                                <span class="badge bg-success">{{ $a->keterangan }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $a->keterangan }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="/absen/{{ $a->id_absensi }}/edit" class="btn btn-warning text-white">
                                                <i class="fa fa-pen" aria-hidden="true"></i>
                                            </a>
                                            <a href="/absen/{{ $a->id_absensi }}/delete"
                                                onclick="return confirm('Apakah kamu yakin ingin menghapus data?')"
                                                class="ms-2 btn btn-danger text-white">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
