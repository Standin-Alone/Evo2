<?php

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

// Route::get('/', function () {
//     return view('voucher-gen');
// });

Route::get('/voucherlist/{p_code}', function($p_code) { 
	return view('voucher-list',['p_code'=>$p_code]);	
});


Route::get('import-form', 'VoucherController@importForm')->name('voucherimport');
Route::post('import','VoucherController@import')->name('voucher.import');
//Route::get('import-form','VoucherController@importchunk')->name('voucher.importdb');


Route::get('GetSubCatAgainstMainCatEdit/{id}','VoucherController@GetSubCatAgainstMainCatEdit');

Route::get('vouchergen/{batch_no}', 'VoucherListController@voucher_view')->name('voucher-generation');	
Route::get('vouchergen/list/{batch_no}', 'VoucherListController@getVouchers')->name('voucher-listing');

Route::get('vouchererrgen/{batch_no}', 'VoucherListController@vouchererr_view')->name('voucher-err-generation');	
Route::get('vouchererrgen/list/{batch_no}', 'VoucherListController@getVouchersErr')->name('voucher-err-listing');

Route::get('batchgen', 'VoucherListController@batch_view')->name('batch-generation');	
Route::get('batchgen/list', 'VoucherListController@getBatch')->name('batch-listing');

Route::get('vouchergen/voucherprint/{ref_no}', 'VoucherListController@getVoucherPrintInd')->name('voucher-printing-ind');
Route::get('vouchergen/voucherprintbatch/{batch_no}', 'VoucherListController@getVoucherPrintBatch')->name('voucher-printing-batch');

Route::get('vouchererrgen/vouchererrprintbatch/{batch_no}', 'VoucherListController@getVoucherErrPrintBatch')->name('voucher-error-printing-batch');

//voucher management

Route::post('vouchergendelete','VoucherListController@DeleteVouch')->name('vouchergen-delete');
Route::post('vouchergenrestore','VoucherListController@RestoreVouch')->name('vouchergen-restore');

Route::get('voucherdelete/{ref_no}', 'VoucherListController@getVoucherDeleteInd')->name('voucher-delete-ind');
Route::get('vouchergen/voucherdeletebatch/{batch_no}', 'VoucherListController@getVoucherDeleteBatch')->name('voucher-delete-batch');

//batch maangement
Route::post('batchgen-delete-batch','VoucherController@DeleteBatch')->name('delete-batch');