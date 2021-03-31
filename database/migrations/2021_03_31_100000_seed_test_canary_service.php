<?php

declare(strict_types=1);

use App\Constants\ServiceVisibility;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

final class SeedTestCanaryService extends Migration
{
    public function up()
    {
        DB::table('services')->insert([
            'name'        => 'Test Canary',
            'description' => 'Service used for testing in God Mode.',
            'handle'      => 'test-canary',
            'visibility'  => ServiceVisibility::RESTRICTED,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down()
    {
        DB::table('services')->where('handle', 'test-canary')->delete();
    }
}
