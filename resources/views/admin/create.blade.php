@extends('layout.main')

@section('title')
    <title>Absensi | Tambah admin</title>
@endsection
@section('content')
    <div class="container">
        <div class="card mt-3">
            <div class="card-header">
                <h4 align="center">Tambah Admin</h4>
            </div>
            <div class="card-body">

                <form action="/admin" method="POST">
                    @csrf

                    <!-- role otomatis -->
                    <input type="hidden" name="role" value="guru">

                    <div class="row">
                        <div class="d-flex">
                            <div class="col-md-6">
                                <label class="mt-2 form-label">Nama</label>
                                <input type="text" class="form-control" name="nama">
                            </div>
                            <div class="col-md-6">
                                <label class="mt-2 form-label">Username</label>
                                <input type="text" class="form-control" name="username">
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="col-md-12">
                                <label class="mt-3 form-label">Password</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-dark mt-4">SUBMIT</button>
                </form>

            </div>
        </div>
    </div>
@endsection
