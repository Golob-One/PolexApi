<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;


/**
 * App\Models\Parrainage
 *
 * @property int $id
 * @property string $num_electeur
 * @property string $prenom
 * @property string $nom
 * @property string $nin
 * @property string $region
 * @property int $parti_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $date_expir
 * @property-read \App\Models\Parti $parti
 * @method static \Database\Factories\ParrainageFactory factory($count = null, $state = [])
 * @method static Builder|Parrainage newModelQuery()
 * @method static Builder|Parrainage newQuery()
 * @method static Builder|Parrainage query()
 * @method static Builder|Parrainage whereCreatedAt($value)
 * @method static Builder|Parrainage whereDateExpir($value)
 * @method static Builder|Parrainage whereId($value)
 * @method static Builder|Parrainage whereNin($value)
 * @method static Builder|Parrainage whereNom($value)
 * @method static Builder|Parrainage whereNumElecteur($value)
 * @method static Builder|Parrainage wherePartiId($value)
 * @method static Builder|Parrainage wherePrenom($value)
 * @method static Builder|Parrainage whereRegion($value)
 * @method static Builder|Parrainage whereUpdatedAt($value)
 */
class ParrainageFinal extends Model
{
    protected $table = "parrainages_final";
    use HasFactory;
    protected $fillable = ["num_electeur","prenom", "nom", "nin","date_expir", "created_at","updated_at", "commune","region","parti_id","user_id", "doublon"];


    public function getDateExpirAttribute()
    {
        if ($this->attributes['date_expir'] !=null) {
            try {
                return Carbon::createFromFormat('d/m/Y', $this->attributes['date_expir'])->format('d/m/Y');
            } catch (\Exception $e) {
                Log::error("unable to format attribute date_expir  with details".$e->getMessage());
                return $this->attributes['date_expir'];
            }
        }

        return null;
    }
    protected $casts = [
        'doublon' => 'boolean',
    ];
}
