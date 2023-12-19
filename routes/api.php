<?php

use App\Http\Controllers\ParrainageController;
use App\Models\Company;
use App\Models\Params;
use App\Models\Parrainage;
use App\Models\ParrainageFinal;
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
Route::get('parrainages/final/all', function (){
        return ParrainageFinal::where("id",">",0)->orderBy("created_at")->paginate(1000);
    })->withoutMiddleware("throttle:api");
Route::get('parrainages/final/region/{region}', function ($region){
//    dd(DB::table('parrainages_final')->select(['*'])->where("region","LIKE",$region)->orderBy("created_at")->toRawSql()
//);
         return ParrainageFinal::where("region","LIKE",$region)->orderBy("created_at")->paginate(500);
    });
Route::get('parrainages/final/index', function (){
     $regions
     = ParrainageFinal::select('region as nom', DB::raw('count(*) as nombre'))
    ->groupBy('region')
    ->get();
     $users
     = ParrainageFinal::select('user_id as user', DB::raw('count(*) as nombre'))
    ->groupBy('user_id')
    ->get();
     return ["regions"=>$regions,"users"=>$users, "total"=>ParrainageFinal::count()];

//         return ParrainageFinal::where("region","LIKE",$region)->orderBy("created_at")->paginate(500);
    });
Route::delete('parrainages/delete/{parrainage}',[ParrainageController::class,'destroy']);
Route::get('parrainages/find/{param}',[ParrainageController::class,'findOne']);
Route::post('parrainages/excel', [ParrainageController::class,"bulkInsertFromExcel"])->withoutMiddleware("throttle:api");
Route::post('parrainages/update/{num_electeur}', [ParrainageController::class,"update"]);
Route::post('parrainages/search', [ParrainageController::class,"search"]);
Route::apiResource("parrainages", ParrainageController::class);

