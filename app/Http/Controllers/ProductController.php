<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        try{
            $users = Product::get();
            return response()->json([
                "code" => "00",
                "info" => "Mengambil list product berhasil",
                "data" => $users
            ]);
        }catch(Exception $e){
            return response()->json([
                "code" => "-1",
                "info" => "Mengambil list prodcuct gagal",
                "data" => null
            ], 500);
        }
    }

    public function store(Request $request)
    {

        $rules = [
            "nama" => "required",
            "harga" => "required|numeric",
            "is_diskon" => "required|boolean",
            "harga_diskon" => "nullable|required_if:is_diskon,true|numeric|lt:harga",
            "stock" => "required|numeric",
            "image_url" => "required|url",
        ];

        $messages = [
            "required" => ":attribute wajib diisi",
            "url" => ":attribute merupakan link yang tidak valid",
            "numeric" => ":attribute harus berupa angka",
            "harga_diskon.lt" => ":attribute tidak boleh lebih dari harga asli",
            "harga_diskon.required_if" => ":attribute dibutuhkan jika diskon menyala",
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

        $data = [
            "nama" => $request->nama,
            "harga" => $request->harga,
            "harga_diskon" => $request->harga_diskon ?? null,
            "is_diskon" => $request->is_diskon,
            "stock" => $request->stock,
            "image_url" => $request->image_url,
        ];

        $user = Product::create($request->all());

        return response()->json([
            "code" => "00",
            "info" => "Membuat Product berhasil",
            "data" => $user
        ]);
    }

    public function delete(Request $request, Product $product)
    {
        $check = $product->delete();

        if($check){
            return response()->json([
                "code" => "00",
                "info" => "Menghapus Product berhasil",
                "data" => new \stdClass
            ]);
        }

        return response()->json([
            "code" => "-1",
            "info" => "Menghapus Product gagal",
            "data" => new \stdClass
        ], 500);
    }
}