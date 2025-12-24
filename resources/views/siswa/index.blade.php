@extends('layout.main')
@section('title')
<title> Absensi | Data siswa </title>
@endsection

@section('content')
{{-- CSS kecil untuk icon panah di select --}}
<style>
    .select-wrapper {
        position: relative;
    }

    .select-wrapper select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 2rem; /* ruang untuk icon */
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
        transform: translateY(-50%) rotate(180deg); /* panah ke atas */
    }
</style>

<div class="container" style="margin-bottom: 100px">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Data Siswa</h4>

                {{-- FILTER KELAS + TAMBAH SISWA --}}
                <div class="d-flex">
                    <form action="{{ url('/siswa') }}" method="GET" class="d-flex align-items-center me-2">
                        <div class="select-wrapper me-2" id="kelas-wrapper-siswa">
                            <select name="kelas" class="form-control" id="kelas-select-siswa">
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

                    <a href="/siswa/create" class="btn text-white btn-success">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table mt-4 table-bordered mb-4" id="myTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>No Telepon</th>
                        <th>Kelas</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $s)
                        <tr>
                            <td width="5%">{{ $s->id_siswa }}</td>
                            <td width="25%">{{ $s->nama }}</td>
                            <td width="15%">{{ $s->notelp }}</td>
                            {{-- Kelas dari kolom email --}}
                            <td width="15%">{{ $s->email }}</td>
                            <td>{{ $s->alamat }}</td>
                            <td width="10%">
                                <div class="d-flex">
                                    <a href="/siswa/{{ $s->id_siswa }}" class="btn btn-warning text-white">
                                        <i class="fa fa-pen" aria-hidden="true"></i>
                                    </a>
                                    <a href="/siswa/{{ $s->id_siswa }}/delete"
                                       onclick="return confirm('Apakah kamu yakin ingin menghapus data?')"
                                       class="ms-2 btn btn-danger text-white">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script untuk memutar panah saat select fokus/blur --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('kelas-select-siswa');
        const wrapper = document.getElementById('kelas-wrapper-siswa');

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