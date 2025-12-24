<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AbsenController extends Controller
{
    /**
     * Dashboard.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $absens   = Absensi::orderBy('id_absensi', 'desc')->get();
        $absennow = Absensi::where('tanggal', Carbon::now()->format('Y/m/d'))->get();

        return view('index', compact('absens', 'absennow'));
    }

    /**
     * Halaman form absen.
     */
    public function absen()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $today = Carbon::now()->format('Y/m/d');
        $loop  = [];

        $s = Siswa::rightJoin('absensis', 'siswas.id_siswa', '=', 'absensis.id_siswa')
            ->where('tanggal', $today)
            ->get();

        foreach ($s as $value) {
            $loop[] = $value->nama;
        }

        $siswa = Siswa::whereNotIn('nama', $loop)->get();
        $tgl   = Carbon::now();

        return view('absen', compact('siswa', 'tgl', 's'));
    }

    public function create()
    {
        //
    }

    /**
     * Simpan absen baru.
     */
    public function post(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $request->validate([
            'id_siswa'   => 'required',
            'keterangan' => 'required',
            'tanggal'    => 'required',
        ]);

        Absensi::create($request->all());

        Alert::success('Success', 'Data berhasil dibuat');

        // kembali ke halaman form absen lagi
        return redirect()->back();
    }

    public function show($id)
    {
        //
    }

    /**
     * Edit absen.
     */
    public function edit($id_absensi)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $today = Carbon::now()->format('Y/m/d');
        $loop  = [];

        $s = Siswa::rightJoin('absensis', 'siswas.id_siswa', '=', 'absensis.id_siswa')
            ->where('tanggal', $today)
            ->get();

        foreach ($s as $value) {
            $loop[] = $value->nama;
        }

        $siswa = Siswa::whereNotIn('nama', $loop)->get();
        $tgl   = Carbon::now();
        $absen = Absensi::where('id_absensi', $id_absensi)->first();

        return view('edit', compact('absen', 'tgl', 'siswa'));
    }

    /**
     * Update absen.
     */
    public function update(Request $request, $id_absensi)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $absen = Absensi::findOrFail($id_absensi);
        $absen->update($request->all());

        Alert::success('Success', 'Data berhasil di-update');

        return redirect('/dashboard');
    }

    /**
     * Hapus absen.
     */
    public function destroy($id_absensi)
    {
        $absen = Absensi::where('id_absensi', $id_absensi)->first();
        $absen->delete();

        Alert::success('Success', 'Data berhasil dihapus');

        return back();
    }

    /**
     * Report default (tanpa filter tanggal request).
     * Default: hari ini sampai 1 minggu ke depan.
     */
    public function report()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        // default: hari ini s.d. 1 minggu ke depan
        $from = Carbon::today()->format('Y-m-d');
        $to   = Carbon::today()->addWeek()->format('Y-m-d');

        // semua siswa + relasi absensi
        $m = Siswa::with('absensis')->get();

        // daftar kelas unik dari kolom email
        $listKelas = Siswa::whereNotNull('email')
            ->distinct()
            ->pluck('email');

        return view('report', compact('m', 'from', 'to', 'listKelas'));
    }

    /**
     * Report dengan filter tanggal + kelas.
     */
    public function reportsearch(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        // jika tidak diisi di form, pakai default (hari ini s.d. 1 minggu ke depan)
        $from = $request->from ?: Carbon::today()->format('Y-m-d');
        $to   = $request->to   ?: Carbon::today()->addWeek()->format('Y-m-d');

        // query siswa + relasi absensi
        $query = Siswa::with('absensis');

        // filter kelas pakai kolom email
        if ($request->filled('kelas')) {
            $query->where('email', $request->kelas);
        }

        $m = $query->get();

        // daftar kelas untuk dropdown
        $listKelas = Siswa::whereNotNull('email')
            ->distinct()
            ->pluck('email');

        return view('report', compact('m', 'from', 'to', 'listKelas'));
    }
}