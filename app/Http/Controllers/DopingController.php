<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class DopingController extends Controller
{
    public function index()
    {
        $count = 0;
        return view('index',['count'=>$count]);
        // return view('index');
    }
    public function cari(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'kategori' => 'required',
        ]);
        $kategori = $request->kategori;
        if ($kategori=="nama_zat") {
            $kategori = "tb_zat.nama_zat";
        }
        $count = 0;
        if ($validasi->fails()) {
            return view('index',['count'=>$count])->with('error', 'Kategori Tidak Boleh Kosong');
        }else{
            $cari = $request->cari;
            $product = DB::table('tb_produk')
            ->rightJoin('tb_zat', 'tb_produk.id_zat', '=', 'tb_zat.id_zat')
            ->where($kategori, 'like', "%".$cari."%")
            ->where($kategori, '!=', '')
            ->where($kategori, '!=', 'N/A')
            ->get();
            $count = $product->count();
            return view('index',['products'=>$product,'count'=>$count]);
        }
    }
    public function carix(Request $request, $kategoris)
    {   
        if ($kategoris=="nama_zat") {
            $results = DB::table('tb_zat')
            ->select($kategoris)
            ->where($kategoris, '!=', '')
            ->get();    
        }else {
            $results = DB::table('tb_produk')
            ->select($kategoris)
            ->where($kategoris, '!=', '')
            ->where($kategoris, '!=', 'N/A')
            ->get();    
        }
        
        
        return response()->json(['results'=>$results]);
        // return Response::json(['results', $categories]);
        // return $request->all();
        // return $categories;
    }
}
