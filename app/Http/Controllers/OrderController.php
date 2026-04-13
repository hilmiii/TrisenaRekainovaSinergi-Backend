<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Membuat pesanan baru (Checkout)
    public function store(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'items'        => 'required|array', 
        ]);

        $userId = $request->user()?->id;
        $savedOrders = [];

        foreach ($request->items as $item) {
            $savedOrders[] = Order::create([
                'user_id'          => $userId,
                'order_number'     => $request->order_number,
                'customer_name'    => $request->customer_name,
                'customer_email'   => $request->customer_email,
                'customer_phone'   => $request->customer_phone,
                'company'          => $request->company,
                'position'         => $request->position,
                'address'          => $request->address,
                'product_name'     => $item['product_name'],
                'material'         => $item['material'] ?? '-',
                'size'             => $item['size'] ?? '-',
                'color'            => $item['color'] ?? '-',
                'additional_notes' => $item['additional_notes'] ?? '',
                'quantity'         => $item['quantity'],
                'total_price'      => $item['total_price'],
                'status' => 'Penawaran',
            ]);
        }

        return response()->json([
            'message' => 'Pesanan berhasil dibuat!',
            'orders'  => $savedOrders
        ], 201);
    }

    // Mengambil riwayat pesanan milik user yang sedang login
    public function myOrders(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    // Mengambil semua pesanan (Admin Dashboard)
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return response()->json($orders);
    }

    // Admin mengubah status dan/atau resi pesanan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Penawaran,Pre-Order,Cancel,Proses,Dikirim,Selesai',
            'courier'         => 'nullable|string',
            'tracking_number' => 'nullable|string',
        ]);

        $order = Order::find($id);
        
        if (!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        $updateData = ['status' => $request->status];
        
        if ($request->status === 'Dikirim') {
            $updateData['courier'] = $request->courier;
            $updateData['tracking_number'] = $request->tracking_number;
        }

        // Memperbarui semua barang dengan Nomor Pesanan yang sama
        Order::where('order_number', $order->order_number)->update($updateData);

        return response()->json([
            'message' => 'Status seluruh pesanan ' . $order->order_number . ' berhasil diperbarui'
        ]);
    }

    // Admin menghapus pesanan
    public function destroy($id)
    {
        $order = Order::find($id);
        
        if (!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }
        
        // Memastikan seluruh barang dalam satu invoice/nomor pesanan ikut terhapus
        Order::where('order_number', $order->order_number)->delete();
        
        return response()->json(['message' => 'Seluruh pesanan berhasil dihapus']);
    }
}