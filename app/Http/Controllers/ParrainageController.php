<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParrainageRequest;
use App\Http\Requests\UpdateParrainageRequest;
use App\Models\Parrainage;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class ParrainageController extends Controller
{

    public function index(): array
    {

        $rapports["today_count"] = Parrainage::whereDate("created_at",Carbon::today()->toDateString())->count();
        $rapports["total_saisi"] = Parrainage::count();
        $rapports["regions"] = Parrainage::select('region as nom', DB::raw('count(*) as nombre'))
            ->groupBy('region')
            ->get();
        $rapports["users"] = Parrainage::select('user_id as user', DB::raw('count(*) as nombre'))
            ->groupBy('user')
            ->get();
        $rapports["today_counts_per_user"] =
            /* Parrainage::select('user_id as user', DB::raw('count(*) as nombre'))
                 ->whereDate("created_at",Carbon::today()->toDateString())
                 ->groupBy('user')
                 ->get();*/
            DB::select('SELECT user_id as user,
    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) AS nombre,
        COUNT(CASE WHEN created_at >= CURDATE() - INTERVAL 7 DAY THEN 1 END) AS week_count,
    COUNT(*) AS total_count
FROM
    parrainages
       GROUP BY user_id');

        return $rapports;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreParrainageRequest $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        //
        $data = $request->input();
        $request->validate([
            'nin' => [function($attribute,$value, $fail) use ($data){
                $electeur = Parrainage::where("nin",$data["nin"])
                    ->first();
                if ($electeur != null){
                    //no match
                    $fail('Un parrainage déjà enregistré avec la même cni ');
                }
            }],
            'num_electeur' => [function($attribute,$value, $fail) use ($data){
                $electeur =
                    Parrainage::where('num_electeur',$data['num_electeur'])
                        ->first();
                if ($electeur != null){
                    //no match
                    $fail('Un parrainage déjà enregistré avec le même numéro électeur! ');
                }
            }],
        ]);
        $parrainage =  Parrainage::create($data);
        return \response()->json(["parrainage"=>$parrainage, "today_count"=>Parrainage::whereUserId($data['user_id'])->whereDate("created_at",Carbon::today()->toDateString())->count()]);
    }

    /**
     * Display the specified resource.
     *
     * @param Parrainage $parrainage
     * @return Parrainage
     */
    public function show(Parrainage $parrainage): Parrainage
    {
        //
        return $parrainage;
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Parrainage|JsonResponse
     */
    public function update(Request $request, $num_electeur)
    {
        $parrainage = Parrainage::whereNumElecteur($num_electeur)->first();
        if ($parrainage != null){
            $parrainage->update($request->input());
        }else{
            return \response()->json(["message"=>"Parrainage introuvable ! "],404);
        }
        return  $parrainage;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Parrainage $parrainage
     * @return Response
     */
    public function destroy(Parrainage $parrainage): Response
    {
        $hash = '$2y$10$tPiX.HNM8QDjBTs.6lJPxenRD7MN5Ag4m752XZoiTBlysv7G19Em2';

        $secret =\request()->input("secret");
        if (!Hash::check($secret, $hash)){
            abort(403,"Hash value n'est pas valide");
        }
        $parrainage->delete();
        return new Response('deleted',204);
    }

    public function bulkInsertFromExcel(): JsonResponse
    {

        $data = request()->json('data');

        Parrainage::insertOrIgnore($data);

        return response()->json(["total_inserted"=>count($data)]);


    }
    public function findOne($param)
    {
        $electeur = Parrainage::where("nin",$param)
            ->orWhere("num_electeur",$param)
            ->first();
        if ($electeur == null){
            return response()->json(['message'=>'not found'],404);
        }
        return $electeur;
    }

    public function search(Request $request): array
    {
        $hash = env('PARTI_SECRET');
        $secret = $request->input("secret");
        $sql = $request->input("query");

        if ($hash != $secret){
            abort(403,"Le code secret n'est pas valide !");
        }

        return DB::select($sql);

    }
}
