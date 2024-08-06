<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Auth;

//login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Dashboard
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('auth');
Route::get('/kasir/dashboard', [KasirController::class, 'index'])->name('kasir.dashboard')->middleware('auth');
Route::get('/tenant/dashboard', [TenantController::class, 'home'])->name('tenant.dashboard')->middleware('auth');

//users
Route::get('/users', function () {
    return view('userRole');
})->middleware('auth')->name('users');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/form', [UserController::class, 'createForm'])->name('users.create');
Route::post('/users/create', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'editID'])->name('users.edit');
Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');

Route::get('/penjualan', [TransactionController::class, 'penjualan'])->name('penjualan');
Route::get('/pembayaran', [TransactionController::class, 'pembayaran'])->name('pembayaran');


Route::resource('tenants', TenantController::class);
Route::get('tenants/{tenant}/transactions', [TenantController::class, 'transactions'])->name('tenants.transactions');
// Rute CRUD untuk menu
Route::prefix('tenants/{tenant}')->group(function () {
    Route::get('menus/create', [TenantController::class, 'createMenu'])->name('tenants.createMenu');
    Route::post('menus', [TenantController::class, 'storeMenu'])->name('tenants.storeMenu');
    Route::get('menus/{menu}/edit', [TenantController::class, 'editMenu'])->name('tenants.editMenu');
    Route::put('menus/{menu}', [TenantController::class, 'updateMenu'])->name('tenants.updateMenu');
    Route::delete('menus/{menu}', [TenantController::class, 'destroyMenu'])->name('tenants.destroyMenu');
});

Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::post('/save-transaction', [TransactionController::class, 'saveTransaction'])->name('save-transaction');
Route::get('/payment-detail', [TransactionController::class, 'paymentDetail'])->name('payment-detail');
Route::get('/search-menus', [MenuController::class, 'search'])->name('menus.search');
Route::get('/kasir/{id}/menus', [TenantController::class, 'showAllMenus'])->name('tenant.menus.all');
Route::get('/kasir/menus', [MenuController::class, 'index'])->name('menus.index');
Route::get('kasir/tenants', [TenantController::class, 'indexTenant'])->name('tenant.indextenant');
Route::get('/tenant/{id}/menus', [TenantController::class, 'showTenantMenu'])->name('tenant.showmenu');
Route::get('/kasir/edit-order', [TransactionController::class, 'editOrder'])->name('kasir.edit.order');
Route::get('/kasir/daily-report', [KasirController::class, 'dailyReport'])->name('kasir.dailyReport');

Route::get('/report/pejualan', [TransactionController::class, 'penjualan'])->name('reports.sales');
Route::get('/report/{tenant_id}/penjualan', [TenantController::class, 'salesDetails'])->name('tenant.salesDetails');

Route::get('/link-tenant-to-user/form', [TenantController::class, 'showLinkForm'])->name('tenant.link.form');
Route::post('/link-tenant-to-user', [TenantController::class, 'linkTenantToUser'])->name('tenant.link');
Route::get('/link-tenant-to-user/data', [TenantController::class, 'showUserTenant'])->name('tenant.link.data');

Route::get('/tenant/sales-details', [TenantController::class, 'showlaporan'])->name('tenants.salesDetails');
Route::get('/menu', [TenantController::class, 'showmenu'])->name('showmenu');
Route::get('/menu/{id}/edit', [TenantController::class, 'showupdate'])->name('tenants.updatemenu');
Route::put('menu/{id}', [TenantController::class, 'updatemenutenant'])->name('tenant.updateMenu');
Route::delete('/menu/{id}', [TenantController::class, 'hapus'])->name('tenants.deleteMenu');
Route::get('/menu/create', [TenantController::class, 'createmenutenant'])->name('createMenu');
Route::post('/menu', [TenantController::class, 'storemenutenant'])->name('storeMenu');

// Route::post('/save-transaction', [KasirController::class, 'saveTransaction'])->name('saveTransaction');
// // routes/web.php
// Route::get('/tenant/{id}/notifications', [TenantController::class, 'showNotifications'])->name('tenant.notifications');
// Route::post('/tenant/{id}/notifications/{transactionId}/approve', [TenantController::class, 'approveTransaction'])->name('tenant.approveTransaction');

// Route untuk menampilkan detail transaksi
// Route::get('/tenant/order/', [TenantController::class, 'shownotif'])
//     ->name('tenant.transactions.show');

// // Route untuk menyetujui transaksi
// Route::post('/tenant/order/approve', [TenantController::class, 'approve'])
//     ->name('tenant.transactions.approve');

Route::get('/tenant/orders', [TenantController::class, 'showOrders'])->name('tenant.orders');

// routes/web.php
Route::post('/orders/accept/{id}', [KasirController::class, 'acceptOrder'])->name('orders.accept');
Route::post('/orders/reject/{id}', [KasirController::class, 'rejectOrder'])->name('orders.reject');
Route::post('/transactions/temp-store', [TransactionController::class, 'tempStore'])->name('transactions.tempStore');
