<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedAwsS3Service extends Migration
{
    public function up()
    {
        DB::table('services')->insert([
            'name'       => 'AWS S3',
            'handle'     => 'aws-s3',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        DB::table('services')->where('handle', 'aws-s3')->delete();
    }
}
