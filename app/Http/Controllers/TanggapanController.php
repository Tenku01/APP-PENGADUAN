<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengaduan;
use App\Models\Tanggapan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;


class TanggapanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::table('pengaduan')->where('id', $request->pengaduan_id)->update([
            'status'=> $request->status,
        ]);

        $petugas_id = Auth::user()->id;

        $data = $request->all();

        $data['pengaduan_id'] = $request->pengaduan_id;
        $data['petugas_id']=$petugas_id;

        Alert::success('Berhasil', 'Pengaduan berhasil ditanggapi');
        Tanggapan::create($data);
        return redirect('admin/pengaduans');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Pengaduan::with(['details', 'user'])->findOrFail($id);
    
        // Ambil tanggapan terbaru berdasarkan pengaduan_id, diurutkan berdasarkan id terbesar
        $tangap = Tanggapan::where('pengaduan_id', $id)
            ->latest('id')  // Mengurutkan berdasarkan id terbesar
            ->first();  // Ambil yang pertama, yang berarti yang terbaru
    
        // Debug untuk memeriksa apakah tanggapan yang benar diambil
        dd($tangap);
    
        return view('pages.admin.tanggapan.add', [
            'item' => $item,
            'tangap' => $tangap // Kirim tanggapan terbaru ke view
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
        $item = Pengaduan::findOrFail($id);

        // Menampilkan view edit dengan data pengaduan
        return view('admin.pengaduan.edit', compact('item'));
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
            // Validasi input tanggapan
            $request->validate([
                'tanggapan' => 'required|string',
            ]);
        
            // Mengambil data pengaduan berdasarkan ID
            $item = Pengaduan::findOrFail($id);
        
            // Mengupdate data tanggapan
            $item->tanggapan = $request->input('tanggapan');
            $item->status = 'Sedang di Proses';  // Menyatakan status diubah sesuai proses
        
            // Menyimpan perubahan ke database
            $item->save();
        
            // Redirect kembali ke halaman detail dengan pesan sukses
            return redirect()->route('pengaduans.show', $item->id)
                             ->with('success', 'Tanggapan berhasil diperbarui!');
        
    }        
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
