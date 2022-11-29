<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // $faker = Faker\Factory::create();
        $min = 40;
        DB::table('registrasi')->truncate();
        DB::table('peserta')->truncate();
        DB::table('pertandingan')->truncate();

        for ($i=40 ; $i <= 200 ; $i++ ) { 
            
            
            
            
            DB::table('registrasi')->insert([
                'idregistrasi' => $i,
                'idhint' => 1,
                'jawaban' => 'andi',
                'email' => Str::random(8)."@gmail.com",
                'namaregistrasi' => Str::random(8),
                'password' => Hash::make('andi'),
            ]);

            $jk = ['l','p'];

            DB::table('peserta')->insert([
                'idpeserta' => $i,
                'namapeserta' => "Peserta (".$i.")",
                'jk' => $jk[rand(0,1)],
                'kontingen' => 'INKADO',
                'wa' => '081268293603',
                'gambar' => 'none',
            ]);

            DB::table('pertandingan')->insert([
                'idpertandingan' => $i,
                'idkelas' => rand(3,6),
                'idpeserta' => $i,
                'idbagian' => $jk[rand(0,1)],
                'idlomba' => 1,
                'sah' => false,
            ]);



        }


    }
}
