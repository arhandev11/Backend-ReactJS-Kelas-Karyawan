<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        try{
            $articles = Article::whereNotNull('user_id')->with('user')->latest()->get();

            return response()->json([
                "code" => "00",
                "info" => "Mengambil list Artikel berhasil",
                "data" => $articles
            ]);
        }catch(Exception $e){
            return response()->json([
                "code" => "-1",
                "info" => "Mengambil list Artikel gagal",
                "data" => null
            ], 500);
        }
    }

    public function indexWithoutUser()
    {
        sleep(1);
        try{
            $articles = Article::whereNull('user_id')->latest()->get();
            return response()->json([
                "code" => "00",
                "info" => "Mengambil list Artikel berhasil",
                "data" => $articles
            ]);
        }catch(Exception $e){
            return response()->json([
                "code" => "-1",
                "info" => "Mengambil list Artikel gagal",
                "data" => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $rules = [
            "judul" => "required",
            "konten" => "required",
            "highlight" => "nullable|boolean",
            "image_url" => "required|url",
        ];

        $messages = [
            "required" => ":attribute wajib diisi",
            "url" => ":attribute merupakan link yang tidak valid",
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
            "judul" => $request->judul,
            "konten" => $request->konten,
            "highlight" => $request->highlight ?? 0,
            "image_url" => $request->image_url,
            "user_id" => Auth::user()->id,

        ];
            
        $user = Article::create($data);

        return response()->json([
            "code" => "00",
            "info" => "Membuat Artikel berhasil",
            "data" => $user
        ]);
    }

    public function storeWithoutUser(Request $request)
    {
        sleep(1);
        $rules = [
            "judul" => "required",
            "konten" => "required",
            "highlight" => "nullable|boolean",
            "image_url" => "required|url",
        ];

        $messages = [
            "required" => ":attribute wajib diisi",
            "url" => ":attribute merupakan link yang tidak valid",
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
            "judul" => $request->judul,
            "konten" => $request->konten,
            "highlight" => $request->highlight ?? 0,
            "image_url" => $request->image_url,
        ];
            
        $user = Article::create($data);

        return response()->json([
            "code" => "00",
            "info" => "Membuat Artikel berhasil",
            "data" => $user
        ]);
    }

    public function show(Request $request, Article $article)
    {
        sleep(1);

        return response()->json([
            "code" => "00",
            "info" => "Menampillkan Artikel berhasil",
            "data" => $article
        ]);
        

    }
    public function update(Request $request, Article $article)
    {
        sleep(1);

        $rules = [
            "judul" => "required",
            "konten" => "required",
            "highlight" => "nullable|boolean",
            "image_url" => "required|url",
        ];

        $messages = [
            "required" => ":attribute wajib diisi",
            "url" => ":attribute merupakan link yang tidak valid",
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
            "judul" => $request->judul,
            "konten" => $request->konten,
            "highlight" => $request->highlight ?? 0,
            "image_url" => $request->image_url,
        ];
            
        $check = $article->update($data);
        if($check){
            return response()->json([
                "code" => "00",
                "info" => "Update Artikel berhasil",
                "data" => $article
            ]);
        }
        return response()->json([
            "code" => "-1",
            "info" => "Update Artikel gagal",
            "data" => new \stdClass
        ], 500);
    }
    public function delete(Request $request, Article $article)
    {
        sleep(1);

        $check = $article->delete();

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
