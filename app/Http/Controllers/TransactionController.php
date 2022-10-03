<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionHasProduct;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class TransactionController extends Controller
{
    public function index()
    {
        try{
            $userId = Auth::user()->id;
            $transactions = Transaction::where('user_id', $userId)->with('products')->latest()->get();
            return response()->json([
                "code" => "00",
                "info" => "Mengambil list transaksi berhasil",
                "data" => $transactions
            ]);
        }catch(Exception $e){
            return response()->json([
                "code" => "-1",
                "info" => "Mengambil list transaksi gagal",
                "data" => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $rules = [
                "nama_penerima" => "required|string",
                "handphone" => "required|numeric",
                "alamat" => "required|string",
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
            
            $userId = Auth::user()->id;
            $carts = Cart::where('user_id', $userId)->with('product');
            if(!$carts->exists())
            {
                return response()->json([
                    "code" => "-1",
                    "info" => "Keranjang Kosong",
                    "data" => new stdClass
                ], 400);
            }

            $carts = $carts->get();

             $data = [
                "nama_penerima" => $request->nama_penerima,
                "handphone" => $request->handphone,
                "alamat" => $request->alamat,
                "status" => "unpaid",
                "user_id" => $userId,
                "total" => 0
            ];

            $transaction = Transaction::create($data);

            $total = 0;
            $arrTmp = [];
            foreach($carts as $key => $cart)
            {
                $arrTmp[$key]['product_id'] = $cart->product->id;
                $arrTmp[$key]['qty'] = $cart->qty;
                $arrTmp[$key]['transaction_id'] = $transaction->id;
                $arrTmp[$key]['created_at'] = Carbon::now();
                $arrTmp[$key]['updated_at'] = Carbon::now();
                if($cart->product->is_diskon === 1){
                    $total += $cart->product->harga_diskon * $cart->qty;
                }else{
                    $total += $cart->product->harga * $cart->qty;
                }
            }
            $transactionHasProduct = TransactionHasProduct::insert($arrTmp);
            $transaction->total = $total;
            $transaction->save();

            $carts = Cart::where('user_id', $userId)->delete();

            DB::commit();
            return response()->json([
                "code" => "00",
                "info" => "Membuat Keranjang berhasil",
                "data" => $transaction->load('products')
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                "code" => "-1",
                "info" => "Membuat transaksi gagal",
                "data" => null
            ], 500);
        }
    }
}
