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
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        // daftar kelas unik dari siswas.email
        $listKelas = Siswa::whereNotNull('email')
            ->distinct()
            ->pluck('email');

        // query absen total
        $absenQuery = Absensi::with('siswa')
            ->orderBy('id_absensi', 'desc');

        // query absen hari ini
        $absenNowQuery = Absensi::with('siswa')
            ->where('tanggal', Carbon::now()->format('Y/m/d'));

        // jika ada filter kelas (kelas = kolom email di tabel siswas)
        if ($request->filled('kelas')) {
            $kelas = $request->kelas;

            $absenQuery->whereHas('siswa', function ($q) use ($kelas) {
                $q->where('email', $kelas);
            });

            $absenNowQuery->whereHas('siswa', function ($q) use ($kelas) {
                $q->where('email', $kelas);
            });
        }

        $absens   = $absenQuery->get();
        $absennow = $absenNowQuery->get();

        return view('index', compact('absens', 'absennow', 'listKelas'));
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
     */
    public function report()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $from = Carbon::today()->format('Y-m-d');
        $to   = Carbon::today()->addWeek()->format('Y-m-d');

        $m = Siswa::with('absensis')->get();

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

        $from = $request->from ?? Carbon::today()->format('Y-m-d');
        $to   = $request->to   ?? Carbon::today()->addWeek()->format('Y-m-d');

        $query = Siswa::with('absensis');

        if ($request->filled('kelas')) {
            $query->where('email', $request->kelas);
        }

        $m = $query->get();

        $listKelas = Siswa::whereNotNull('email')
            ->distinct()
            ->pluck('email');

        return view('report', compact('m', 'from', 'to', 'listKelas'));
    }
}