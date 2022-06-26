<?php

use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\SubscriberFieldController;
use App\Http\Controllers\SubscriberStateController;
use App\Http\Controllers\FieldTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return response()->json([
        'subscribers_url' => route('subscribers.index').'?filter[{field}]={query}{&filter,sort}',
        'subscriber_url' => route('subscribers.index').'/{subscriber_id}',
        'fields_url' => route('fields.index').'?filter[{field}]={query}{&filter,sort}',
        'field_url' => route('fields.index').'/{field_id}',
        'subscriber_fields_url' => route('subscribers.index').'/{subscriber_id}/fields?filter[{field}]={query}{&filter,sort}',
        'subscriber_field_url' => route('subscribers.index').'/{subscriber_id}/fields/{field_id}',
        'subscriber_states_url' => route('subscribers.states'),
        'field_types_url' => route('fields.types')
    ]);
});

Route::get('/subscriber_states', [SubscriberStateController::class, 'index'])->name('subscribers.states');
Route::get('/field_types', [FieldTypeController::class, 'index'])->name('fields.types');

Route::controller(SubscriberController::class)->group(function () {
    Route::get('/subscribers', 'index')->name('subscribers.index');
    Route::post('/subscribers', 'store');
    Route::get('/subscribers/export', 'export');
    Route::get('/subscribers/{subscriber}', 'show');
    Route::patch('/subscribers/{subscriber}', 'update');
    Route::delete('/subscribers/{subscriber}', 'destroy');
});


Route::controller(FieldController::class)->group(function () {
    Route::get('/fields', 'index')->name('fields.index');
    Route::post('/fields', 'store');
    Route::get('/fields/export', 'export');
    Route::get('/fields/{field}', 'show');
    Route::patch('/fields/{field}', 'update');
    Route::delete('/fields/{field}', 'destroy');
});

Route::controller(SubscriberFieldController::class)->group(function () {
    Route::get('/subscribers/{subscriber}/fields', 'index');
    Route::post('/subscribers/{subscriber}/fields', 'store');
});
