<?php

declare(strict_types=1);

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained()->cascadeOnDelete();
            $table->boolean('via_mail')->default(false);
            $table->string('mail_route')->nullable();
            $table->text('mail_message')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->index('notified_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
