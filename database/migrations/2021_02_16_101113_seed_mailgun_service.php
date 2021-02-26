<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedMailgunService extends Migration
{
    public function up()
    {
        DB::table('services')->insert([
            'name'        => 'Mailgun',
            'description' => 'The email service for developers',
            'handle'      => 'mailgun',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down()
    {
        DB::table('services')->where('handle', 'mailgun')->delete();
    }
}
