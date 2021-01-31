<?php

use Illuminate\Routing\Router;
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

Route::group(['namespace' => 'API\V1', 'prefix' => 'v1'], function (Router $route) {
    $route->get('/stats', 'StatsController@index')->name('stats');
    $route->get('/campaigns', 'CampaignController@index')->name('campaigns');
    $route->middleware('refine.mail.content')->post('/campaigns', 'CampaignController@store')->name('send-campaign');
});
