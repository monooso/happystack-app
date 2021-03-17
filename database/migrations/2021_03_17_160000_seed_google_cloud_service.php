<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

final class SeedGoogleCloudService extends Migration
{
    public function up()
    {
        DB::table('services')->insert([
            'name'        => 'Google Cloud',
            'description' => 'Google cloud computing services',
            'handle'      => 'google-cloud',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down()
    {
        DB::table('services')->where('handle', 'google-cloud')->delete();
    }
}
