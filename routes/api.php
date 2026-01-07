<?php

use App\Http\Controllers\API\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('company.token')->group(function () {

    Route::get('/get-ticket-types', [TicketController::class, 'getTicketTypes']);
    Route::post('/ticket-submit', [TicketController::class, 'submitTicket']);
    Route::get('/company-tickets', [TicketController::class, 'getCompanyTickets']);
    Route::get('/ticket-details/{ticket_id}', [TicketController::class, 'getTicketDetails']);
    Route::post('/submit-ticket-comment', [TicketController::class, 'submitTicketComment']);

});

