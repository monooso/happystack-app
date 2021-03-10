<?php

declare(strict_types=1);

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateAgencyChannelsTable extends Migration
{
    public function up()
    {
        Schema::create('agency_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained();
            $table->string('type');
            $table->string('route');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agency_channels');
    }
}
