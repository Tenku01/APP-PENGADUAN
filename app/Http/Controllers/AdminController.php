<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Pengaduan;

// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade as PDF;


class AdminController extends Controller
{
    public function __invoke()
    {

    }



    public function masyarakat() {

        $data = DB::table('users')->where('roles','=', 'USER')->get();

        return view('pages.admin.masyarakat', [
            'data' => $data
        ]);
    }

    public function laporan()
    {
        $pengaduan = Pengaduan::orderByRaw('FIELD(status, "Selesai", "Sedang di Proses", "Belum di Proses")')
                              ->get();
    
        return view('pages.admin.laporan', compact('pengaduan'));
    }
    

    public function cetak() {

        $pengaduan = Pengaduan::all();

        $pdf = PDF::loadview('pages.admin.pengaduan',[
            'pengaduan' => $pengaduan
        ]);
        return $pdf->download('laporan.pdf');
    }

    public function pdf($id) {

        $pengaduan = Pengaduan::find($id);

        $pdf = PDF::loadview('pages.admin.pengaduan.cetak', compact('pengaduan'))->setPaper('a4');
        return $pdf->download('laporan-pengaduan.pdf');
    }
}
