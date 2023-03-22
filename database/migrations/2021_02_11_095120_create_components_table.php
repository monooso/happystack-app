<?php

declare(strict_types=1);

use App\Constants\Status;
use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('handle');
            $table->enum('status', Status::all())->default(Status::UNKNOWN);
            $table->timestamp('status_updated_at')->nullable();
            $table->timestamps();

            $table->unique(['service_id', 'name']);
            $table->unique(['service_id', 'handle']);

            $table->index('status');
            $table->index('status_updated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('components');
    }
};
