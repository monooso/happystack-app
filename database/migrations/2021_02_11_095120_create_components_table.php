<?php

use App\Constants\Status;
use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentsTable extends Migration
{
    public function up()
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Service::class)->constrained();
            $table->string('name');
            $table->string('handle');
            $table->enum('current_status', Status::all())->default(Status::UNKNOWN);
            $table->timestamps();

            $table->unique(['service_id', 'name']);
            $table->unique(['service_id', 'handle']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('components');
    }
}
