@extends('layout.main')

@section('title')
<title> Absensi | Report </title>
<style>
    @media print{
       .sticky-footer,.waktu,.heder,.btn{display: none;}
        #navbar{display: none}
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">

            {{-- FORM FILTER --}}
            <form action="/report/cari" method="get" class="mb-4">
                <div class="row align-items-end">

                    {{-- Dari Tanggal --}}
                    <div class="col-md-3 mb-3 waktu">
                        <label class="form-label">Dari Tanggal :</label>
                        <input
                            type="date"
                            name="from"
                            class="form-control"
                            required
                            value="{{ request('from', $from) }}"
                        >
                    </div>

                    {{-- Sampai Tanggal --}}
                    <div class="col-md-3 mb-3 waktu">
                        <label class="form-label">Sampai Tanggal :</label>
                        <input
                            type="date"
                            name="to"
                            class="form-control"
                            required
                            value="{{ request('to', $to) }}"
                        >
                    </div>

                    {{-- Kelas (dari kolom email) --}}
                    <div class="col-md-3 mb-3 waktu">
                        <label class="form-label">Kelas :</label>
                        <select name="kelas" class="form-control">
                            <option value="">Semua Kelas</option>
                            @foreach ($listKelas as $kelas)
                                <option value="{{ $kelas }}"
                                    {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                    {{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Filter --}}
                    <div class="col-md-3 mb-3 waktu text-md-right text-start">
                        <button type="submit"
                                name="filter"
                                class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>
                </div>

                {{-- Tombol Print di baris sendiri --}}
                <div class="row">
                    <div class="col-md-3 mb-3 waktu">
                        <button type="button"
                                class="btn btn-outline-success w-100"
                                onclick="window.print()">
                            Print laporan
                        </button>
                    </div>
                </div>
            </form>

            {{-- HEADER LAPORAN --}}
            <div class="mt-2">
                <h3 class="text-center mb-4">Laporan Absensi</h3>
                <h6>Dari Tanggal : {{ $from }}</h6>
                <h6>Sampai Tanggal : {{ $to }}</h6>
                @if(request('kelas'))
                    <h6>Kelas : {{ request('kelas') }}</h6>
                @endif
            </div>

            {{-- TABEL LAPORAN --}}
            <div class="MyTable">
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Hadir</th>
                            <th>Ijin</th>
                            <th>Sakit</th>
                            <th>Alfa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @if ($m)
                            @foreach ($m as $a)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $a->nama }}</td>
                                    {{-- Kelas diambil dari kolom email pada tabel siswas --}}
                                    <td>{{ $a->email ?? '-' }}</td>

                                    {{-- Hadir --}}
                                    <td>
                                        @php
                                            $hadir = $a->absensis
                                                ->where('keterangan', 'Hadir')
                                                ->whereBetween('tanggal', [$from, $to])
                                                ->count();
                                        @endphp
                                        {{ $hadir == 0 ? '-' : $hadir }}
                                    </td>

                                    {{-- Ijin --}}
                                    <td>
                                        @php
                                            $ijin = $a->absensis
                                                ->where('keterangan', 'Ijin')
                                                ->whereBetween('tanggal', [$from, $to])
                                                ->count();
                                        @endphp
                                        {{ $ijin == 0 ? '-' : $ijin }}
                                    </td>

                                    {{-- Sakit --}}
                                    <td>
                                        @php
                                            $sakit = $a->absensis
                                                ->where('keterangan', 'Sakit')
                                                ->whereBetween('tanggal', [$from, $to])
                                                ->count();
                                        @endphp
                                        {{ $sakit == 0 ? '-' : $sakit }}
                                    </td>

                                    {{-- Alfa --}}
                                    <td>
                                        @php
                                            $alfa = $a->absensis
                                                ->where('keterangan', 'Alfa')
                                                ->whereBetween('tanggal', [$from, $to])
                                                ->count();
                                        @endphp
                                        {{ $alfa == 0 ? '-' : $alfa }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection