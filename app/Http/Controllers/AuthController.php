<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use stdClass;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $code = "00";
        $data = [];
        try{
            $code = "10";
            $rules = [
                "email" => "required",
                "password" => "required",
            ];
        
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json([
                    "code" => "-1",
                    "info" => $validator->messages()->first(),
                    "data" => [
                        "errors" => $validator->messages()->toArray()
                        ]
                ], 400);
            }
            $user = User::where('email', $request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password)){
                return response()->json([
                    "code" => "00",
                    "info" => "Email atau password yang dimasukkan salah",
                    "data" => new stdClass
                ], 400);
            }
            
            $token = $user->createToken($request->device_name ?? "token")->plainTextToken;
            $code = "20";
            
            $data['user'] = $user;
            $data['token'] = $token;

            return response()->json([
                "code" => "00",
                "info" => "Login Berhasil",
                "data" => $data
            ]);
        }catch(Exception $e){
            return response()->json([
                "code" => "-1",
                "info" => "Login Gagal",
                "data" => null
            ], 500);
        }
    }

    public function register(Request $request)
    {
        $code = "00";
        $data = [];
        try{
            $code = "10";
            $rules = [
                "email" => "required|unique:users,email",
                "username" => "required|unique:users,username",
                "name" => "required",
                "password" => "required|confirmed",
            ];
        
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json([
                    "code" => "-1",
                    "info" => $validator->messages()->first(),
                    "data" => [
                        "errors" => $validator->messages()->toArray()
                        ]
                ], 400);
            }
            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            $code = "20";
            
            $data['user'] = $user;

            return response()->json([
                "code" => "00",
                "info" => "Register Berhasil",
                "data" => $data
            ]);
        }catch(Exception $e){
            return response()->json([
                "code" => "-1",
                "info" => "Register Gagal",
                "data" => null
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        $code = "00";
        $data = [];
        try{
            $user = Auth::user();
            
            $data['user'] = $user;

            return response()->json([
                "code" => "00",
                "info" => "Mengambil data profile Berhasil",
                "data" => $data
            ]);
        }catch(Exception $e){
            return response()->json([
                "code" => "-1",
                "info" => "Mengambil data profile Gagal",
                "data" => null
            ], 500);
        }
    }
}
