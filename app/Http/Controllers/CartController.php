<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class CartController extends Controller
{
    public function index()
    {
        try{
            $userId = Auth::user()->id;
            $carts = Cart::where('user_id', $userId)->with('product')->latest()->get();
            return response()->json([
                "code" => "00",
                "info" => "Mengambil list keranjang berhasil",
                "data" => $carts
            ]);
        }catch(Exception $e){
            return response()->json([
                "code" => "-1",
                "info" => "Mengambil list keranjang gagal",
                "data" => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
             $rules = [
                "product_id" => "required|exists:products,id"
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

            $product = Product::where('id', $request->product_id)->first();

            if($product->stock <= 0)
            {
                return response()->json([
                    "code" => "-1",
                    "info" => "Stok Produk Habis",
                    "data" => new stdClass
                ]);
            }
            
            $userId = Auth::user()->id;

            if(
                Cart::where('product_id', $request->product_id)
                    ->where('user_Id', $userId)
                    ->exists()
            )
            {
                $cart = Cart::where('product_id', $request->product_id)
                ->where('user_Id', $userId)
                ->delete();
            }else{
                $product->stock -= 1;
                $product->save();
            }

            $data = [
                "product_id" => $request->product_id,
                "user_id" => $userId,
                "qty" => 1
            ];

            $cart = Cart::create($data);
            DB::commit();
            return response()->json([
                "code" => "00",
                "info" => "Membuat Keranjang berhasil",
                "data" => $cart
            ]);
        }catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "code" => "-1",
                "info" => "Membuat transaksi gagal",
                "data" => null
            ], 500);
        }
       
    }

     public function delete(Request $request, Cart $cart)
    {
        $check = $cart->delete();

        if($check){
            return response()->json([
                "code" => "00",
                "info" => "Menghapus keranjang berhasil",
                "data" => new \stdClass
            ]);
        }

        return response()->json([
            "code" => "-1",
            "info" => "Menghapus keranjang gagal",
            "data" => new \stdClass
        ], 500);
    }
}
