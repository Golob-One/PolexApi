<?php

use App\Models\Parti;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parrainages', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('num_electeur')->nullable(false);
            $table->string('prenom',190)->nullable(false);
            $table->string('nom',20)->nullable(false);
            $table->string('nin',15)->nullable(false);
            $table->string('region',30)->nullable(false);
            $table->string('date_expir')->nullable(false);
            $table->boolean('generated',)->nullable()->default(false);
            $table->bigInteger("parti_id")->nullable(true);
            $table->bigInteger("user_id")->nullable(true);
            $table->timestamps();


        });
    }

    public function down()
    {
        Schema::dropIfExists('parrainages');
    }
};
