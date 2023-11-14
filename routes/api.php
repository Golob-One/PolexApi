<?php

use App\Http\Controllers\ParrainageController;
use App\Models\Company;
use App\Models\Params;
use App\Models\Parrainage;
use App\Models\Parti;
use App\Models\User;
use App\Policies\RoleNames;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

Route::get('parrainages/region/{region}', function ($region){
        return Parrainage::whereRegion($region)->orderBy("created_at")->paginate(500);
    });
Route::delete('parrainages/delete/{parrainage}',[ParrainageController::class,'destroy']);
Route::get('parrainages/find/{param}',[ParrainageController::class,'findOne']);
Route::post('parrainages/excel', [ParrainageController::class,"bulkInsertFromExcel"])->withoutMiddleware("throttle:api");
Route::post('parrainages/update/{num_electeur}', [ParrainageController::class,"update"]);
Route::post('parrainages/search', [ParrainageController::class,"search"]);
Route::apiResource("parrainages", ParrainageController::class);

