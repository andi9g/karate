<?php

namespace App\Http\Controllers;

use App\Models\pesertaM;
use App\Models\registrasiM;
use App\Models\pertandinganM;
use App\Models\kelasM;
use App\Models\lombaM;
use Hash;
use PDF;
use Illuminate\Http\Request;

class daftarC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = empty($request->keyword)?"":$request->keyword;

        $kontingen = pesertaM::groupBy('kontingen')->select('kontingen')->get();
        
        $perlombaan = lombaM::where('ket', true)->get(); 

        $kelas = kelasM::get();

        $peserta = pesertaM::join('registrasi', 'registrasi.idregistrasi', 'peserta.idpeserta')
        ->join('pertandingan', 'pertandingan.idpeserta', 'peserta.idpeserta')
        ->join('kelas', 'kelas.idkelas', 'pertandingan.idkelas')
        ->join('lomba', 'lomba.idlomba', 'pertandingan.idlomba')
        ->where('lomba.ket', true)
        ->where(function ($query) use ($keyword) {
            $query->where('peserta.namapeserta', 'like', "%$keyword%");
        })
        ->select('peserta.*', 'pertandingan.*', 'kelas.namakelas', 'lomba.akses')
        ->orderBy('pertandingan.created_at', 'desc')
        ->paginate(20);
        
        $peserta->appends($request->only(["keyword", 'limit']));
        
        return view('pages.daftar', [
            'peserta' => $peserta,
            'kontingen' => $kontingen,
            'kelas' => $kelas,
            'perlombaan' => $perlombaan,
        ]);
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
        $request->validate([
            'namapeserta' => 'required',
            'kontingen' => 'required',
            'jk' => 'required',
            'kelaspertandingan' => 'required',
            'lomba' => 'required',
        ]);
        
        
        try{
        

            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $originName = $gambar->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $gambar->getClientOriginalExtension();
                $size = $gambar->getSize();
                $format = strtolower($extension);
                if($format == 'jpg' || $format == 'jpeg' || $format == 'png') {
                    //request data
                    
                    $fileName = str_replace(" ","",$fileName).'_'.time().uniqid().'.'.$extension;
                    $kirim = $gambar->move(public_path('/img/peserta'), $fileName);
                    // $kirim = Storage::disk("local")->put("gambar/peserta/".$fileName, file_get_contents($gambar));
                    $fileName = url('img/peserta', [$fileName]);

                }
            }else {
                return redirect()->back()->with('success', 'Gambar tidak boleh kosong');
            }


            if($kirim){
                $namapeserta = $request->namapeserta;
                $email = strtolower($namapeserta);
                $email = str_replace(" ", "", $email)."@gmail.com";
                $idhint = 1;
                $jawaban = "andi";
                $wa = "123456789";
                $kontingen = strtoupper($request->kontingen);
                $idlomba = $request->lomba;
                $idkelas = $request->kelaspertandingan;
                $jk = $request->jk;
                $password = Hash::make("newpassword");

                $cek = registrasiM::where('email', $email)->count();
                if($cek == 0) {
                    $reg = new registrasiM;
                    $reg->idhint = $idhint;
                    $reg->jawaban = $jawaban;
                    $reg->email = $email;
                    $reg->namaregistrasi = $namapeserta;
                    $reg->password = $password;
                    $reg->save();
                }
                $cek = registrasiM::where('email', $email)->first();
                $idpeserta = $cek->idregistrasi;

                $cek = pesertaM::where('idpeserta', $idpeserta)->count();
                if($cek == 0) {
                    $pst = new pesertaM;
                    $pst->idpeserta = $idpeserta;
                    $pst->namapeserta = $namapeserta;
                    $pst->jk = $jk;
                    $pst->kontingen = $kontingen;
                    $pst->wa = $wa;
                    $pst->gambar = $fileName;
                    $pst->save();
                }

                $cek = pesertaM::where('idpeserta', $idpeserta)->first();
                $idpeserta = $cek->idpeserta;
                $idbagian = $cek->jk;

                $cek = pertandinganM::where('idkelas', $idkelas)->where('idlomba', $idlomba)
                ->where('idpeserta', $idpeserta)->count();

                if($cek == 0) {
                    $store = new pertandinganM;
                    $store->idkelas = $idkelas;
                    $store->idpeserta = $idpeserta;
                    $store->idbagian = $idbagian;
                    $store->idlomba = $idlomba;
                    $store->sah = false;
                    $store->save();
                    if($store) {
                        return redirect()->back()->with('toast_success', 'success');
                    }
                }
            }
            
            return redirect()->back()->with('toast_error', 'Kelas Pertandingan Telah Terdaftar');
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function pengesahan(Request $request, $idpertandingan)
    {
        $sah = (boolean) $request->sah;

        try{
            $update = pertandinganM::where('idpertandingan', $idpertandingan)->update([
                'sah' => $sah,
            ]);

            if ($update) {
                return redirect()->back()->with('toast_success', 'success');
            }

        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\pesertaM  $pesertaM
     * @return \Illuminate\Http\Response
     */
    public function show(pesertaM $pesertaM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pesertaM  $pesertaM
     * @return \Illuminate\Http\Response
     */
    public function edit(pesertaM $pesertaM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\pesertaM  $pesertaM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, pesertaM $pesertaM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pesertaM  $pesertaM
     * @return \Illuminate\Http\Response
     */
    public function destroy(pesertaM $pesertaM)
    {
        //
    }
}
