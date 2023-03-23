<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('services')->insert([
            'name' => 'AWS SNS',
            'description' => 'Amazon Simple Notification Service',
            'handle' => 'aws-sns',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('services')->where('handle', 'aws-sns')->delete();
    }
};
