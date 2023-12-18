<?php

use App\Models\Parti;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parrainages_final', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('num_electeur')->nullable(false)->unique();
            $table->string('prenom',190)->nullable(false);
            $table->string('nom',20)->nullable(false);
            $table->string('nin',15)->nullable(false)->unique();
            $table->string('region',30)->nullable(false);
            $table->string('date_expir')->nullable(false);
            $table->boolean('doublon')->nullable()->default(false);
            $table->string('commune')->nullable();
            $table->bigInteger("parti_id")->nullable();
            $table->bigInteger("user_id")->nullable();
            $table->timestamps();


        });
    }

    public function down()
    {
        Schema::dropIfExists('parrainages_final');
    }
};
