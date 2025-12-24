@extends('layout.main')

@section('title')
    <title>Absensi | Dashboard</title>
@endsection

@section('content')
    <style>
        .select-wrapper {
            position: relative;
        }
        .select-wrapper select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 2rem;
        }
        .select-wrapper::after {
            content: '\25BC';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.8rem;
            pointer-events: none;
            transition: transform 0.2s ease;
        }
        .select-wrapper.open::after {
            transform: translateY(-50%) rotate(180deg);
        }
    </style>

    <div class="container">
        <h4 class="mr-4 d-none d-lg-inline text-black bold">Welcome, {{ Auth()->user()->nama }}</h4>

        <div class="card mt-4">
            <div class="card-body">
                @php
                    $totalHadir = 0;
                    $totalIjin  = 0;
                    $totalSakit = 0;
                    $totalAlfa  = 0;

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

                {{-- Kartu ringkasan --}}
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
                        <div class="d-flex justify-content-between align-items-center">
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
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
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
                                        <td>{{ optional($a->siswa)->nama ?? '-' }}</td>
                                        <td>{{ optional($a->siswa)->email ?? '-' }}</td>
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
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Absen Total</h4>

                            {{-- Filter Kelas untuk Absen Total --}}
                            <form action="{{ url('/dashboard') }}" method="GET" class="d-flex align-items-center">
                                <div class="select-wrapper me-2" id="kelas-wrapper-dashboard">
                                    <select name="kelas" class="form-control" id="kelas-select-dashboard">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($listKelas as $kelas)
                                            <option value="{{ $kelas }}"
                                                {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                                {{ $kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Filter
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="myTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
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
                                        <td>{{ optional($a->siswa)->nama ?? '-' }}</td>
                                        <td>{{ optional($a->siswa)->email ?? '-' }}</td>
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

    {{-- Script untuk panah kecil di dropdown kelas dashboard --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('kelas-select-dashboard');
            const wrapper = document.getElementById('kelas-wrapper-dashboard');

            if (select && wrapper) {
                select.addEventListener('focus', function () {
                    wrapper.classList.add('open');
                });
                select.addEventListener('blur', function () {
                    wrapper.classList.remove('open');
                });
                select.addEventListener('click', function () {
                    wrapper.classList.toggle('open');
                });
            }
        });
    </script>
@endsection