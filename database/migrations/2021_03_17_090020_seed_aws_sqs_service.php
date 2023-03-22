<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('services')->insert([
            'name' => 'AWS SQS',
            'description' => 'Amazon Simple Queue Service',
            'handle' => 'aws-sqs',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        DB::table('services')->where('handle', 'aws-sqs')->delete();
    }
};
