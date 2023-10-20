<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class Parrainage extends Model
{
    use HasFactory;
    protected $fillable = ["num_electeur","prenom", "nom", "nin","date_expir", "created_at","updated_at", "region","parti_id","user_id"];

    public function parti(): BelongsTo
    {
        return $this->belongsTo(Parti::class);
    }
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
}
