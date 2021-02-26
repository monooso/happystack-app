<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedAwsSesService extends Migration
{
    public function up()
    {
        DB::table('services')->insert([
            'name'        => 'AWS SES',
            'description' => 'Amazon Simple Email Service.',
            'handle'      => 'aws-ses',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down()
    {
        DB::table('services')->where('handle', 'aws-ses')->delete();
    }
}
