<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        try{
            $users = Product::whereNotNull('user_id')->with('user')->latest()->get();
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

    public function home()
    {
        try{
            $users = Product::whereNotNull('user_id')->with('user')->latest()->get()->take(4);
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

    public function indexWithoutUser()
    {
        try{
            $users = Product::whereNull('user_id')->latest()->get();
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
            "category" => "required|string|in:teknologi,makanan,minuman,kesehatan,lainnya",
            "description" => "nullable|string",
        ];

        $messages = [
            "required" => ":attribute wajib diisi",
            "url" => ":attribute merupakan link yang tidak valid",
            "numeric" => ":attribute harus berupa angka",
            "harga_diskon.lt" => ":attribute tidak boleh lebih dari harga asli",
            "harga_diskon.required_if" => ":attribute dibutuhkan jika diskon menyala",
            "category.in" => ":attribute harus berisi salah satu dari teknologi, makanan, minuman, kesehatan, lainnya",
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
            "harga_diskon" => $request->harga_diskon ?? 0,
            "is_diskon" => $request->is_diskon,
            "stock" => $request->stock,
            "image_url" => $request->image_url,
            "category" => $request->category,
            "description" => $request->description,
            "user_id" => Auth::user()->id,
        ];

        $user = Product::create($data);

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

    public function update(Request $request, Product $product)
    {
        $rules = [
            "nama" => "required",
            "harga" => "required|numeric",
            "is_diskon" => "required|boolean",
            "harga_diskon" => "nullable|required_if:is_diskon,true|numeric|lt:harga",
            "stock" => "required|numeric",
            "image_url" => "required|url",
            "category" => "required|string|in:teknologi,makanan,minuman,kesehatan,lainnya",
            "description" => "nullable|string",
        ];

        $messages = [
            "required" => ":attribute wajib diisi",
            "url" => ":attribute merupakan link yang tidak valid",
            "numeric" => ":attribute harus berupa angka",
            "harga_diskon.lt" => ":attribute tidak boleh lebih dari harga asli",
            "harga_diskon.required_if" => ":attribute dibutuhkan jika diskon menyala",
            "category.in" => ":attribute harus berisi salah satu dari teknologi, makanan, minuman, kesehatan, lainnya",
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
            "harga_diskon" => $request->harga_diskon ?? 0,
            "is_diskon" => $request->is_diskon,
            "stock" => $request->stock,
            "image_url" => $request->image_url,
            "category" => $request->category,
            "description" => $request->description,
        ];


        $check = $product->update($data);

        if($check){
            return response()->json([
                "code" => "00",
                "info" => "Update Product berhasil",
                "data" => $product
            ]);
        }
        return response()->json([
            "code" => "-1",
            "info" => "Update Product gagal",
            "data" => new \stdClass
        ], 500);
    }

    public function storeWithoutUser(Request $request)
    {

        $rules = [
            "nama" => "required",
            "harga" => "required|numeric",
            "is_diskon" => "required|boolean",
            "harga_diskon" => "nullable|required_if:is_diskon,true|numeric|lt:harga",
            "stock" => "required|numeric",
            "image_url" => "required|url",
            "description" => "nullable|string",
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
            "description" => $request->description,
        ];

        $user = Product::create($data);

        return response()->json([
            "code" => "00",
            "info" => "Membuat Product berhasil",
            "data" => $user
        ]);
    }


    public function show(Request $request, Product $product)
    {
        return response()->json([
            "code" => "00",
            "info" => "Mengambil Product berhasil",
            "data" => $product->load('user')
        ]);
    }
}