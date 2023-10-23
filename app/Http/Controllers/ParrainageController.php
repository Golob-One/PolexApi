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
            Parrainage::select('user_id as user', DB::raw('count(*) as nombre'))
                ->whereDate("created_at",Carbon::today()->toDateString())
                ->groupBy('user')
                ->get();

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
        return  Parrainage::create($data);
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
        //
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
        $hash = '$2y$10$tPiX.HNM8QDjBTs.6lJPxenRD7MN5Ag4m752XZoiTBlysv7G19Em2';
        $sql = $request->input("query");

        $secret = $request->input("secret");
        if (!Hash::check($secret, $hash)){
            abort(403,"Hash value n'est pas valide");
        }

        return DB::select($sql);

    }
}
