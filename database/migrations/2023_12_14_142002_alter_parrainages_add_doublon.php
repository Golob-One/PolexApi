<?php

use App\Models\Parti;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parrainages', function (Blueprint $table) {

           $table->boolean("doublon")->default(false);
           $table->boolean("depose")->default(false);


        });
    }

    public function down(): void
    {
        Schema::table('parrainages', function (Blueprint $table) {
            $table->renameColumn("doublon","generated");
            $table->dropColumn("depose");

        });
    }
};
