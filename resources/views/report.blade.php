@extends('layout.main')

@section('title')
<title> Absensi | Report </title>
<style>
    @media print{
        .sticky-footer,.waktu,.heder,.btn{display: none;}
        #navbar{display: none}
    }

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
        content: '\25BC'; /* ▼ */
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
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">

            <form action="/report/cari" method="get">
                <div class="row align-items-end">

                    <div class="col-md-3 waktu mb-4">
                        <label>Dari Tanggal :</label>
                        <input type="date" name="from" class="form-control" required
                               value="{{ request('from', $from) }}">
                    </div>

                    <div class="col-md-3 waktu mb-4">
                        <label>Sampai Tanggal :</label>
                        <input type="date" name="to" class="form-control" required
                               value="{{ request('to', $to) }}">
                    </div>

                    {{-- FILTER KELAS dengan panah --}}
                    <div class="col-md-2 waktu mb-4">
                        <label>Kelas :</label>
                        <div class="select-wrapper" id="kelas-wrapper-report">
                            <select name="kelas" class="form-control" id="kelas-select-report">
                                <option value="">Semua Kelas</option>
                                @foreach ($listKelas as $kelas)
                                    <option value="{{ $kelas }}"
                                        {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                        {{ $kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- TOMBOL FILTER --}}
                    <div class="col-md-2 waktu mb-4">
                        <button type="submit"
                                name="filter"
                                class="btn btn-primary w-100"
                                style="margin-top: 25px">
                            Filter
                        </button>
                    </div>

                    {{-- TOMBOL PRINT --}}
                    <div class="col-md-2 waktu mb-4">
                        <button type="button"
                                class="btn btn-outline-success w-100"
                                style="margin-top: 25px"
                                onclick="window.print()">
                            Print
                        </button>
                    </div>

                </div>
            </form>

            {{-- HEADER --}}
            <div class="mt-2">
                <h3 class="text-center mb-4">Laporan Absensi</h3>
                <h6>Dari Tanggal : {{ $from }}</h6>
                <h6>Sampai Tanggal : {{ $to }}</h6>
                @if(request('kelas'))
                    <h6>Kelas : {{ request('kelas') }}</h6>
                @endif
            </div>

            {{-- TABEL --}}
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
                    @foreach ($m as $a)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $a->nama }}</td>
                            <td>{{ $a->email ?? '-' }}</td>

                            <td>{{ $a->absensis->where('keterangan','Hadir')->whereBetween('tanggal',[$from,$to])->count() ?: '-' }}</td>
                            <td>{{ $a->absensis->where('keterangan','Ijin')->whereBetween('tanggal',[$from,$to])->count() ?: '-' }}</td>
                            <td>{{ $a->absensis->where('keterangan','Sakit')->whereBetween('tanggal',[$from,$to])->count() ?: '-' }}</td>
                            <td>{{ $a->absensis->where('keterangan','Alfa')->whereBetween('tanggal',[$from,$to])->count() ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectReport = document.getElementById('kelas-select-report');
        const wrapperReport = document.getElementById('kelas-wrapper-report');

        if (selectReport && wrapperReport) {
            selectReport.addEventListener('focus', function () {
                wrapperReport.classList.add('open');
            });
            selectReport.addEventListener('blur', function () {
                wrapperReport.classList.remove('open');
            });
            selectReport.addEventListener('click', function () {
                wrapperReport.classList.toggle('open');
            });
        }
    });
</script>
@endsection