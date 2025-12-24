<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        // query dasar
        $query = Siswa::query();

        // jika ada filter kelas, gunakan kolom email sebagai kelas
        if ($request->filled('kelas')) {
            $query->where('email', $request->kelas);
        }

        $siswa = $query->get();

        // daftar kelas unik dari kolom email untuk dropdown filter
        $listKelas = Siswa::whereNotNull('email')
            ->distinct()
            ->pluck('email');

        return view('siswa.index', compact('siswa', 'listKelas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        return view('siswa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $request->validate([
            'nama'   => 'required',
            'email'  => 'nullable',
            'alamat' => 'nullable'
        ]);

        Siswa::create($request->all());
        Alert::success('Success', 'Data berhasil ditambah');
        return redirect('/siswa');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id_siswa)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $siswa = Siswa::findOrFail($id_siswa);
        return view('siswa.show', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_siswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_siswa)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $siswa = Siswa::findOrFail($id_siswa);
        $siswa->update($request->all());
        Alert::success('Success', 'Data berhasil Di Update');
        return redirect('/siswa');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id_siswa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_siswa)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $siswa = Siswa::findOrFail($id_siswa);
        $siswa->delete();
        Alert::success('Success', 'Data berhasil dihapus');
        return back();
    }
}