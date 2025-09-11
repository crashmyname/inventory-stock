<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaneController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.sign-in');
});
// API
Route::post('/apidata',[ApiController::class, 'ApiData'])->name('apidata');
// AUTH
Route::get('/login',[AuthController::class, 'login'])->name('login');
Route::post('/login',[AuthController::class, 'onLogin'])->name('onLogin');
Route::get('/logout',[AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function(){
    Route::get('/home',[HomeController::class, 'index'])->name('home');
    Route::get('/mychart',[HomeController::class, 'countChart'])->name('countchart');
    Route::middleware(['role:Administrator,Admin LA,Admin MDF,Admin PP,Admin PO,Admin EVA,Admin ISS,Admin GA'])->group(function(){
        // USER
        Route::get('/user',[UserController::class, 'index'])->name('user');
        Route::post('/cuser',[UserController::class, 'create'])->name('cuser');
        Route::post('/user/{id}',[UserController::class, 'update'])->name('uuser');
        Route::delete('/user/{id}',[UserController::class, 'delete'])->name('duser');
        Route::get('/profile/{id}',[UserController::class, 'profile'])->name('profile');
        Route::post('/profile/{id}',[UserController::class, 'updateProfile'])->name('updateprofile');
        // BARANG
        Route::get('/barang',[BarangController::class, 'index'])->name('barang.index');
        Route::get('/generatecode',[BarangController::class, 'generateCode'])->name('barang.generatecode');
        Route::post('/barang/import',[BarangController::class, 'Import'])->name('barang.import');
        Route::post('/barang',[BarangController::class, 'create'])->name('barang.create');
        Route::post('/barang/{id}',[BarangController::class, 'update'])->name('barang.update');
        Route::delete('/barang/{id}',[BarangController::class, 'delete'])->name('barang.delete');
        // LANE
        Route::get('/lane',[LaneController::class, 'index'])->name('lane.index');
        Route::post('/lane',[LaneController::class, 'create'])->name('lane.create');
        Route::post('/lane/{id}',[LaneController::class, 'update'])->name('lane.update');
        Route::delete('/lane/{id}',[LaneController::class, 'delete'])->name('lane.delete');
        // SUPPLIER
        Route::get('/supplier',[SupplierController::class, 'index'])->name('supplier.index');
        Route::post('/supplier',[SupplierController::class, 'create'])->name('supplier.create');
        Route::post('/supplier/import',[SupplierController::class, 'Import'])->name('supplier.import');
        Route::post('/supplier/{id}',[SupplierController::class, 'update'])->name('supplier.update');
        Route::delete('/supplier/{id}',[SupplierController::class, 'delete'])->name('supplier.delete');
        // STOCK
        Route::get('/stockla',[StockController::class, 'stockla'])->name('stockla');
        Route::get('/stockpp',[StockController::class, 'stockpp'])->name('stockpp');
        Route::get('/stockeva',[StockController::class, 'stockeva'])->name('stockeva');
        Route::get('/stockpo',[StockController::class, 'stockpo'])->name('stockpo');
        Route::get('/stockmdf',[StockController::class, 'stockmdf'])->name('stockmdf');
        Route::get('/stockga',[StockController::class, 'stockga'])->name('stockga');
        Route::get('/stockiss',[StockController::class, 'stockiss'])->name('stockiss');
        Route::post('/getbarang',[BarangController::class, 'getBarang'])->name('getbarang');
        Route::post('/stock',[StockController::class, 'create'])->name('stock.create');
        Route::post('/stock/import',[StockController::class, 'Import'])->name('stock.import');
        Route::post('/stock/{id}',[StockController::class, 'update'])->name('stock.update');
        Route::delete('/stock/{id}',[StockController::class, 'delete'])->name('stock.delete');
        // TRANSACTIONS
        Route::get('/transaction',[TransactionController::class, 'index'])->name('transaction.index');
        Route::get('/gettransaction',[TransactionController::class, 'getTransaction'])->name('transaction.data');
        Route::post('/transaction',[TransactionController::class, 'create'])->name('transaction.create');
        Route::post('/transaction/{id}',[TransactionController::class, 'update'])->name('transaction.update');
        Route::delete('/transaction/{id}',[TransactionController::class, 'delete'])->name('transaction.delete');
        Route::get('/test',[TransactionController::class, 'SentEmail'])->name('test');

        // REPORTS
        Route::get('/reportla',[ReportController::class, 'reportla'])->name('reportla');
        Route::get('/detailla/{id}/{startdate}/{enddate}',[ReportController::class, 'DetailLA'])->name('detailla');

        Route::get('/reportmdf',[ReportController::class, 'reportmdf'])->name('reportmdf');
        Route::get('/detailmdf/{id}/{startdate}/{enddate}',[ReportController::class, 'DetailMDF'])->name('detailmdf');

        Route::get('/reportga',[ReportController::class, 'reportga'])->name('reportga');
        Route::get('/detailga/{id}/{startdate}/{enddate}',[ReportController::class, 'DetailGA'])->name('detailga');

        Route::get('/reportiss',[ReportController::class, 'reportiss'])->name('reportiss');
        Route::get('/detailiss/{id}/{startdate}/{enddate}',[ReportController::class, 'DetailISS'])->name('detailiss');

        Route::get('/reportpp',[ReportController::class, 'reportpp'])->name('reportpp');
        Route::get('/detailpp/{id}/{startdate}/{enddate}',[ReportController::class, 'DetailPP'])->name('detailpp');

        Route::get('/reporteva',[ReportController::class, 'reporteva'])->name('reporteva');
        Route::get('/detaileva/{id}/{startdate}/{enddate}',[ReportController::class, 'DetailEVA'])->name('detaileva');

        Route::get('/reportpo',[ReportController::class, 'reportpo'])->name('reportpo');
        Route::get('/detailpo/{id}/{startdate}/{enddate}',[ReportController::class, 'DetailPO'])->name('detailpo');

        Route::post('/report',[ReportController::class, 'create'])->name('report.create');
        Route::post('/report/{id}',[ReportController::class, 'update'])->name('report.update');
        Route::delete('/report/{id}',[ReportController::class, 'delete'])->name('report.delete');
    });
});
