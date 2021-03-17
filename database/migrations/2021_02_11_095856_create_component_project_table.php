<?php

declare(strict_types=1);

use App\Models\Component;
use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateComponentProjectTable extends Migration
{
    public function up()
    {
        Schema::create('component_project', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained();
            $table->foreignIdFor(Component::class)->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('component_project');
    }
}
