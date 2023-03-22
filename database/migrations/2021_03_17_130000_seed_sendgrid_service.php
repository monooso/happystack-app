<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('services')->insert([
            'name' => 'SendGrid',
            'description' => 'Transactional email provider',
            'handle' => 'sendgrid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        DB::table('services')->where('handle', 'sendgrid')->delete();
    }
};
