<?php

declare(strict_types=1);

use App\Constants\Status;
use App\Models\Component;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateStatusUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('status_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Component::class)->constrained()->cascadeOnDelete();
            $table->enum('status', Status::all());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('status_updates');
    }
}
