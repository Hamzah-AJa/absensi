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

    /* KHUSUS PDF – TANPA MENGUBAH TAMPILAN LAYAR */
    #pdfContent table {
        width: 100% !important;
        table-layout: fixed;
    }

    #pdfContent th,
    #pdfContent td {
        word-wrap: break-word;
        white-space: normal;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">

            {{-- FILTER --}}
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

                    <div class="col-md-2 waktu mb-4">
                        <button type="submit"
                                class="btn btn-primary w-100"
                                style="margin-top: 25px">
                            Filter
                        </button>
                    </div>

                    {{-- SAVE PDF --}}
                    <div class="col-md-2 waktu mb-4">
                        <button type="button"
                                class="btn btn-outline-success w-100"
                                style="margin-top: 25px"
                                onclick="savePDF()">
                            Save PDF
                        </button>
                    </div>

                </div>
            </form>

            {{-- AREA PDF --}}
            <div id="pdfContent">

                <div class="mt-2">
                    <h3 class="text-center mb-4">Laporan Absensi</h3>
                    <h6>Dari Tanggal : {{ $from }}</h6>
                    <h6>Sampai Tanggal : {{ $to }}</h6>
                    @if(request('kelas'))
                        <h6>Kelas : {{ request('kelas') }}</h6>
                    @endif
                </div>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th style="width:5%">ID</th>
                            <th style="width:35%">Nama</th>
                            <th style="width:15%">Kelas</th>
                            <th style="width:9%">Hadir</th>
                            <th style="width:9%">Ijin</th>
                            <th style="width:9%">Sakit</th>
                            <th style="width:9%">Alfa</th>
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
</div>

{{-- HTML2PDF --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function savePDF() {
        const element = document.getElementById('pdfContent');

        const opt = {
            margin: 0.4,
            filename: 'Laporan_Absensi_{{ $from }}_sd_{{ $to }}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 1.3 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
    }
</script>
@endsection