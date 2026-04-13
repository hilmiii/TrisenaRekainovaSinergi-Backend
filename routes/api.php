<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ForgotPasswordController;

// ==========================================
// 1. Public Routes (Dapat diakses siapa saja)
// ==========================================
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
// Rute Lupa Password
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

// ==========================================
// 2. Protected Routes (Wajib Login Sanctum)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    
    // --- Kelola Profil User ---
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // --- Kelola Pesanan (Customer) ---
    Route::post('/orders', [OrderController::class, 'store']); 
    Route::get('/my-orders', [OrderController::class, 'myOrders']); 
    

    // ==========================================
    // 3. Admin Routes (Wajib Login & Role Admin)
    // ==========================================
    Route::middleware('is_admin')->group(function () {
        
        // --- Kelola Produk ---
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
        
        // --- Kelola Semua Pesanan ---
        Route::get('/admin/orders', [OrderController::class, 'index']);
        Route::put('/admin/orders/{order}/status', [OrderController::class, 'updateStatus']); 
        Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy']);
        
    });
});