<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        try{
            $users = Profile::get();
            return response()->json([
                "code" => "00",
                "info" => "Mengambil list user berhasil",
                "data" => $users
            ]);
        }catch(Exception $e){
            return response()->json([
                "code" => "-1",
                "info" => "Mengambil list user gagal",
                "data" => null
            ], 500);
        }
    }

    public function store(Request $request)
    {

        $rules = [
            "name" => "required",
            "username" => "required",
            "email" => "required",
            "domisili" => "required",
            "alamat" => "required",
        ];

        $messages = [
            "required" => ":attribute wajib diisi"
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()){
             return response()->json([
                "code" => "-1",
                "info" => $validator->messages()->first(),
                "data" => [
                    "errors" => $validator->messages()->toArray()
                ]
            ], 400);
        }

        $user = Profile::create($request->all());

        return response()->json([
            "code" => "00",
            "info" => "Membuat user berhasil",
            "data" => $user
        ]);
    }

     public function delete(Request $request, Profile $profile)
    {
        $check = $profile->delete();

        if($check){
            return response()->json([
                "code" => "00",
                "info" => "Menghapus Artikel berhasil",
                "data" => new \stdClass
            ]);
        }

        return response()->json([
            "code" => "-1",
            "info" => "Menghapus Artikel gagal",
            "data" => new \stdClass
        ], 500);
    }
}