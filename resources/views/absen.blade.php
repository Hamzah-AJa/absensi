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
<div class="container mt-4">
    <div class="card">
        <div class="card-body">

            {{-- FORM FILTER --}}
            <form action="/report/cari" method="get" class="mb-4">
                <div class="row g-3 align-items-end">

                    {{-- Dari Tanggal --}}
                    <div class="col-md-3 waktu">
                        <label class="form-label mb-1">Dari Tanggal</label>
                        <input
                            type="date"
                            name="from"
                            class="form-control"
                            required
                            value="{{ request('from', $from) }}"
                        >
                    </div>

                    {{-- Sampai Tanggal --}}
                    <div class="col-md-3 waktu">
                        <label class="form-label mb-1">Sampai Tanggal</label>
                        <input
                            type="date"
                            name="to"
                            class="form-control"
                            required
                            value="{{ request('to', $to) }}"
                        >
                    </div>

                    {{-- Kelas --}}
                    <div class="col-md-2 waktu">
                        <label class="form-label mb-1">Kelas</label>
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
                    <div class="col-md-2 waktu">
                        <button
                            type="submit"
                            name="filter"
                            class="btn btn-primary w-100"
                            style="margin-top: 30px"
                        >
                            Filter
                        </button>
                    </div>

                    {{-- Tombol Print --}}
                    <div class="col-md-2 waktu">
                        <button
                            type="button"
                            class="btn btn-outline-success w-100"
                            onclick="window.print()"
                            style="margin-top: 30px"
                        >
                            Print
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
            <div class="mt-3">
                <table class="table table-bordered">
                    <thead class="table-light">
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
                        @foreach ($m as $a)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $a->nama }}</td>
                                <td>{{ $a->email ?? '-' }}</td>

                                <td>
                                    @php
                                        $hadir = $a->absensis->where('keterangan','Hadir')
                                            ->whereBetween('tanggal', [$from, $to])->count();
                                    @endphp
                                    {{ $hadir == 0 ? '-' : $hadir }}
                                </td>

                                <td>
                                    @php
                                        $ijin = $a->absensis->where('keterangan','Ijin')
                                            ->whereBetween('tanggal', [$from, $to])->count();
                                    @endphp
                                    {{ $ijin == 0 ? '-' : $ijin }}
                                </td>

                                <td>
                                    @php
                                        $sakit = $a->absensis->where('keterangan','Sakit')
                                            ->whereBetween('tanggal', [$from, $to])->count();
                                    @endphp
                                    {{ $sakit == 0 ? '-' : $sakit }}
                                </td>

                                <td>
                                    @php
                                        $alfa = $a->absensis->where('keterangan','Alfa')
                                            ->whereBetween('tanggal', [$from, $to])->count();
                                    @endphp
                                    {{ $alfa == 0 ? '-' : $alfa }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection