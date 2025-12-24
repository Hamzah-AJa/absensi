@extends('layout.main')

@section('title')
<title>Absensi | Absens</title>
@endsection

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            Absen {{ $tgl->format('D d/m/Y') }}
        </div>
        <div class="card-body">
            <form action="/absen/{{ $absen->id_absensi }}/update" method="POST">
                @csrf

                {{-- Nama siswa --}}
                <label for="siswa" class="mt-2 form-label">
                    Nama Siswa <span style="font-style: italic;">(required)</span>
                </label>
                <select class="form-select" name="id_siswa" id="siswa">
                    <option value="{{ $absen->id_siswa }}" selected>
                        {{ optional($absen->siswa)->nama ?? '-' }}
                    </option>
                    @foreach ($siswa as $s)
                        <option value="{{ $s->id_siswa }}">{{ $s->nama }}</option>
                    @endforeach
                </select>

                {{-- Keterangan --}}
                <label for="Ket" class="mt-2 form-label">
                    Keterangan <span style="font-style: italic;">(required)</span>
                </label>
                <select class="form-select" name="keterangan" id="Ket">
                    <option value="Hadir" {{ $absen->keterangan == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="Sakit" {{ $absen->keterangan == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="Ijin"  {{ $absen->keterangan == 'Ijin'  ? 'selected' : '' }}>Ijin</option>
                    <option value="Alfa"  {{ $absen->keterangan == 'Alfa'  ? 'selected' : '' }}>Alfa</option>
                </select>


                {{-- Tanggal bisa dipilih --}}
                <label for="tanggal" class="mt-2 form-label">
                    Tanggal <span style="font-style: italic;">(required)</span>
                </label>
                <input
                    type="date"
                    class="mt-2 form-control"
                    id="tanggal"
                    name="tanggal"
                    value="{{ \Carbon\Carbon::parse($absen->tanggal)->format('Y-m-d') }}"
                    required
                >

                <div class="mr-2">
                    <button class="float-right btn btn-dark mt-4" type="submit">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
