<?php

namespace App\Http\Controllers;

use App\Models\adminM;
use Illuminate\Support\Collection;
use App\Models\juriM;
use App\Models\lapanganM;
use App\Models\pengaturanM;
use Illuminate\Http\Request;
use Str;
use PDF;
use Hash;
class adminC extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = empty($request->keyword)?"":$request->keyword;

        $lapangan = lapanganM::get();

        $admin = adminM::join('lapangan', 'lapangan.idlapangan', 'admin.idlapangan')
        ->where(function ($query) use ($keyword){
            $query->where('admin.username', 'like', "%$keyword%")
            ->orWhere('lapangan.namalapangan', 'like', "%$keyword%");
        })
        ->paginate(15);

        $admin->appends($request->only(['keyword', 'limit']));

        return view('pages.pagesAdmin', [
            'lapangan' => $lapangan,
            'admin' => $admin,
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

    public function cetak(Request $request, $idadmin)
    {
        $data1 = adminM::join('lapangan', 'lapangan.idlapangan', 'admin.idlapangan')
        ->where('admin.idadmin', $idadmin)
        ->select('admin.*', 'lapangan.namalapangan')
        ->get();
        $admin = [];
        foreach ($data1 as $item) {
            $data2 = juriM::where('idadmin', $item->idadmin)->get();

            $admin[] = collect([
                'username' => $item->username,
                'password' => $item->password2,
                'namalapangan' => $item->namalapangan,
                'juri' => $data2,
            ]);
        }

        $pdf = PDF::loadView('cetak.admin',[
            'admin' => $admin,
        ])->setPaper('a4');

        return $pdf->stream('LoginAdmin.pdf');
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
            'username' => 'required|unique:admin,username',
            'lapangan' => 'required',
        ]);
        
        
        try{
            $jumlahjuri = pengaturanM::first()->jumlahjuri;
            $username = $request->username;
            $lapangan = $request->lapangan;
            $password = strtolower(Str::random(7));
            $hash = Hash::make($password);
            
            $store = new adminM;
            $store->username = $username;
            $store->password = $hash;
            $store->password2 = $password;
            $store->idlapangan = $lapangan;     
            $store->save();
            
            if($store) {
                $admin = adminM::where('username', $username)->first();
                $idadmin = $admin->idadmin;
                for ($i=1; $i <= $jumlahjuri ; $i++) {
                        $usernameJuri = $username."j".$i;
                        $password = strtolower(Str::random(7));
                        $hash = Hash::make($password);

                    $store = new juriM;
                    $store->username = $usernameJuri;
                    $store->password = $hash;
                    $store->password2 = $password;
                    $store->idadmin = $idadmin;
                    $store->posisi = $i;
                    $store->save();
                }

                $location = public_path().'/refresh/'.$username;

                if(file_exists($location."_refresh.php")){
                    unlink($location."_refresh.php");
                }

                $myfile2 = fopen($location."_refresh.php", "w+") or die("Unable to open file!");

                $txt2 = "";
                fwrite($myfile2, $txt2);
                fclose($myfile2);
                chmod($location."_refresh.php", 0777);


                if($store) {
                    return redirect()->back()->with('toast_success', 'success');
                }
            }
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }

    public function reset(Request $request, $idadmin)
    {
        try{
            $password = strtolower(Str::random(7));
            $hash = Hash::make($password);

            
            $update = adminM::where('idadmin', $idadmin)->update([
                'password' => $hash,
                'password2' => $password,
            ]);

            if($update) {
                $data = juriM::where('idadmin', $idadmin)->get();

                foreach ($data as $item) {
                    $password = strtolower(Str::random(7));
                    $hash = Hash::make($password);
                    $update = juriM::where('idjuri', $item->idjuri)->update([
                        'password' => $hash,
                        'password2' => $password,
                    ]);
                }

                if($update){
                    return redirect()->back()->with('toast_success', 'success');
                }

            }

            return redirect('admin')->with('toast_error', 'Terjadi kesalahan');
        }catch(\Throwable $th){
            return redirect('admin')->with('toast_error', 'Terjadi kesalahan');
        }
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
    public function destroy(adminM $adminM,$idadmin)
    {
        try{
            $destroy = adminM::where('idadmin', $idadmin)->delete();
            $destroy = juriM::where('idadmin', $idadmin)->delete();
            if($destroy) {
                return redirect()->back()->with('toast_success', 'success');
            }
        }catch(\Throwable $th){
            return redirect()->back()->with('toast_error', 'Terjadi kesalahan');
        }
    }
}
