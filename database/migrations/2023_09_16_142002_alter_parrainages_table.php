<?php

use App\Models\Parti;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('parrainages', function (Blueprint $table) {

            $table->unique(["num_electeur"]);
            $table->unique(["nin"]);


        });
    }

    public function down()
    {
        Schema::table('parrainages', function (Blueprint $table) {
            $table->dropUnique(["num_electeur","parti_id"]);
            $table->dropUnique(["nin","parti_id"]);
        });
    }
};
