<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Mengambil semua data produk dari database MySQL dan mengubahnya jadi JSON
        $products = Product::all();
        return response()->json($products);
    }
    public function show($slug)
    {
        // Mencari produk berdasarkan slug (atau bisa juga berdasarkan ID)
        $product = Product::where('slug', $slug)->orWhere('id', $slug)->first();

        // Jika produk tidak ada, kembalikan error 404
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json($product);
    }
}