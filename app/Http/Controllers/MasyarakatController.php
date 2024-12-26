<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Tanggapan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use File;

class MasyarakatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->nik;
        // dd($user);

        return view('pages.masyarakat.index', ['liat'=>$user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.masyarakat.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
        'description' => 'required',
        'image' => 'required',
        ]);

        $nik = Auth::user()->nik;
        $id = Auth::user()->id;
        $name = Auth::user()->name;

        $data = $request->all();
        $data['user_nik']=$nik;
        $data['user_id']=$id;
        $data['name']=$name;
        $data['image'] = $request->file('image')->store('assets/laporan', 'public');



        Alert::success('Berhasil', 'Pengaduan terkirim');
        Pengaduan::create($data);
        return redirect('user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function lihat() {
        // Mendapatkan NIK pengguna yang sedang login
        $userNik = Auth::user()->nik;
    
        // Mengambil pengaduan milik pengguna yang sedang login dan mengurutkan berdasarkan status
        $items = Pengaduan::where('user_nik', $userNik)
            ->orderByRaw('FIELD(status, "Belum di Proses", "Sedang di Proses", "Selesai")')
            ->get();
    
        return view('pages.masyarakat.detail', [
            'items' => $items
        ]);
    }
    

    public function show($id)
    {
        // Mengambil pengaduan berdasarkan ID dan mengurutkan berdasarkan status
        $item = Pengaduan::with(['details', 'user'])
            ->where('id', $id)
            ->orderByRaw('FIELD(status, "Belum di Proses", "Sedang di Proses", "Selesai")')
            ->firstOrFail();
    
        // Mengambil tanggapan untuk pengaduan
        $tangap = Tanggapan::where('pengaduan_id', $id)->first();
    
        return view('pages.masyarakat.show', [
            'item' => $item,
            'tangap' => $tangap
        ]);
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
