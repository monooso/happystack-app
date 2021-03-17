<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

final class SeedArcustechService extends Migration
{
    public function up()
    {
        DB::table('services')->insert([
            'name'        => 'Arcustech',
            'description' => 'Managed VPS hosting',
            'handle'      => 'arcustech',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down()
    {
        DB::table('services')->where('handle', 'arcustech')->delete();
    }
}
