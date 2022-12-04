<?php

namespace App\Http\Controllers;

use App\Models\adminM;
use App\Models\juriM;
use App\Models\superadminM;
use Illuminate\Http\Request;
use Hash;

class authC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.login');
    }


    public function proses(Request $request)
    {
        $request->validate([
            'username'=>'required',
            'password'=>'required',
            'posisi'=>'required',
        ]);

        try{
            $username = $request->username;
            $password = $request->password;
            $posisi = $request->posisi;

            
            $proses = false;
            if($posisi == 'admin') {
                $cek = adminM::where('username', $username);
                if($cek->count() == 1) {
                    if(Hash::check($password, $cek->first()->password)){
                        $request->session()->put('login', true);
                        $request->session()->put('id', $cek->first()->idadmin);
                        $request->session()->put('idadmin', $cek->first()->idadmin);
                        $request->session()->put('posisi', 'admin');
                        $request->session()->put('namaadmin', $cek->first()->username);
                        $request->session()->put('idlapangan', $cek->first()->idlapangan);
                        
                        return redirect('tanding')->with('success', 'Welcome ');
                    }
                }else {
                    $request->session()->flush();
                }

                

            }else if($posisi == 'juri') {
                $cek = juriM::join('admin', 'admin.idadmin', 'juri.idadmin')
                ->where('juri.username', $username)
                ->select('juri.*', 'admin.idadmin', 'admin.idlapangan');
                // dd($cek->first()->idlapangan);
                if($cek->count() == 1) {
                    if(Hash::check($password, $cek->first()->password)){
                        $request->session()->put('login', true);
                        $request->session()->put('id', $cek->first()->idjuri);
                        $request->session()->put('posisi', 'juri');
                        $request->session()->put('namaadmin', $cek->first()->username);
                        $request->session()->put('urutan', $cek->first()->posisi);
                        $request->session()->put('idlapangan', $cek->first()->idlapangan);
                        $request->session()->put('idadmin', $cek->first()->idadmin);
                        
                        return redirect('nilai')->with('success', 'Welcome');
                    }
                }else {
                    $request->session()->flush();
                }

            }elseif($posisi == 'superadmin') {
                $cek = superadminM::where('username', $username);
                
                if($cek->count() == 1) {
                    if(Hash::check($password, $cek->first()->password)){
                        $request->session()->put('login', true);
                        $request->session()->put('id', $cek->first()->idsuperadmin);
                        $request->session()->put('posisi', 'superadmin');
                        $request->session()->put('namaadmin', $cek->first()->namasuperadmin);
                        $request->session()->put('urutan', null);
                        $request->session()->put('idlapangan', null);
                        $request->session()->put('idadmin', null);
                        
                        return redirect('home')->with('success', 'Welcome');
                    }
                }else {
                    $request->session()->flush();
                }
            }
            
            return redirect()->back()->with('toast_error', 'Username dan password salah');
            
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Username dan password salah');
        }
    }

    public function ubahpassword(Request $request)
    {
        $request->validate([
            'password1'=>'required',
            'password2'=>'required|same:password1'
        ]);

        try{
            $posisi = $request->session()->get('posisi');
            $id = $request->session()->get('id');
            if($posisi === "admin" || $posisi === "superadmin") {
                $password = Hash::make($request->password1);
                $password2 = $request->password1;

                if($posisi == "admin") {
                    $update = adminM::where('idadmin', $id)->update([
                        'password' => $password,
                        'password2' => $password2,
                    ]);
                }elseif($posisi == "superadmin") {
                    $update = adminM::where('idsuperadmin', $id)->update([
                        'password' => $password,
                    ]);
                }

                if($update) {
                    return redirect()->back()->with('success', 'Success <br> Silahkan login kembali');
                }

            }else {
                return redirect()->back()->with('toast_error', 'Gagal');
            }
            return redirect()->back()->with('toast_error', 'Gagal');
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
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

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function show(adminM $adminM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function edit(adminM $adminM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, adminM $adminM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\adminM  $adminM
     * @return \Illuminate\Http\Response
     */
    public function destroy(adminM $adminM)
    {
        //
    }
}
