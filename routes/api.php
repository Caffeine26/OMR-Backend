<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\CustomerCouponController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\OrderItemOptionController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PromotionItemController;

// Product CRUD routes (all public for testing)
Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('tables', TableController::class);
Route::apiResource('coupons', CouponController::class);
Route::apiResource('customer-coupons', CustomerCouponController::class);
Route::apiResource('order-items', OrderItemController::class);
Route::apiResource('order-options',OrderItemOptionController::class);
Route::apiResource('feedbacks', FeedbackController::class);
Route::apiResource('promotions', PromotionController::class);
Route::apiResource('promotion-item', PromotionItemController::class);



