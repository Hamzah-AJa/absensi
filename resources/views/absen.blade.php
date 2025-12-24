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
                <form action="/absen/post" method="POST">
                    @csrf

                    {{-- FILTER KELAS --}}
                    <label for="filter_kelas" class="mt-2 form-label">
                        Filter Kelas
                    </label>
                    <select id="filter_kelas" class="form-select">
                        <option value="">Semua Kelas</option>
                        @php
                            // ambil daftar kelas unik dari koleksi $siswa (kolom email)
                            $kelasUnik = $siswa->pluck('email')->filter()->unique();
                        @endphp
                        @foreach ($kelasUnik as $k)
                            <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>

                    {{-- NAMA SISWA --}}
                    <label for="siswa" class="mt-2 form-label">
                        Nama Siswa <span style="font-style: italic;">(required)</span>
                    </label>
                    <select class="form-select" name="id_siswa" id="siswa" required>
                        @foreach ($siswa as $s)
                            {{-- data-kelas berisi email (kelas) --}}
                            <option value="{{ $s->id_siswa }}"
                                    data-kelas="{{ $s->email }}"
                                    data-kelas-filter="{{ $s->email }}">
                                {{ $s->nama }}
                            </option>
                        @endforeach
                    </select>

                    {{-- KELAS (otomatis) --}}
                    <label for="kelas" class="mt-2 form-label">
                        Kelas <span style="font-style: italic;">(otomatis)</span>
                    </label>
                    <input type="text" id="kelas" class="form-control mt-2" readonly>

                    {{-- KETERANGAN --}}
                    <label for="Ket" class="mt-2 form-label">
                        Keterangan <span style="font-style: italic;">(required)</span>
                    </label>
                    <select class="form-select" name="keterangan" id="Ket" required>
                        <option value="">Pilih Keterangan</option>
                        <option value="Hadir">Hadir</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Ijin">Ijin</option>
                        <option value="Alfa">Alfa</option>
                    </select>

                    {{-- TANGGAL --}}
                    <label for="tanggal" class="mt-2 form-label">
                        Tanggal <span style="font-style: italic;">(required)</span>
                    </label>
                    <input
                        type="date"
                        name="tanggal"
                        class="mt-2 form-control"
                        id="tanggal"
                        value="{{ $tgl->format('Y-m-d') }}"
                        required
                    >

                    <div class="mr-2">
                        <button class="float-right btn btn-dark mt-4" type="submit">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- script untuk isi kelas otomatis + filter kelas --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectSiswa   = document.getElementById('siswa');
            const inputKelas    = document.getElementById('kelas');
            const filterKelas   = document.getElementById('filter_kelas');

            const allOptions = Array.from(selectSiswa.options);

            function updateKelas() {
                const opt = selectSiswa.options[selectSiswa.selectedIndex];
                inputKelas.value = opt ? (opt.getAttribute('data-kelas') || '') : '';
            }

            function applyFilterKelas() {
                const kelas = filterKelas.value;

                // hapus semua option
                selectSiswa.innerHTML = '';

                // filter dari list awal
                const filtered = allOptions.filter(opt => {
                    const k = opt.getAttribute('data-kelas-filter') || '';
                    return !kelas || k === kelas;
                });

                filtered.forEach(opt => selectSiswa.appendChild(opt));

                // pilih option pertama jika ada
                if (selectSiswa.options.length > 0) {
                    selectSiswa.selectedIndex = 0;
                }

                updateKelas();
            }

            // event
            selectSiswa.addEventListener('change', updateKelas);
            filterKelas.addEventListener('change', applyFilterKelas);

            // inisialisasi awal
            applyFilterKelas();
        });
    </script>
@endsection