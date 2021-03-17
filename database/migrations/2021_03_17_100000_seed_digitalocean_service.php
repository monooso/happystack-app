<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

final class SeedDigitalOceanService extends Migration
{
    public function up()
    {
        DB::table('services')->insert([
            'name'        => 'DigitalOcean',
            'description' => 'The developer cloud',
            'handle'      => 'digitalocean',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down()
    {
        DB::table('services')->where('handle', 'digitalocean')->delete();
    }
}
