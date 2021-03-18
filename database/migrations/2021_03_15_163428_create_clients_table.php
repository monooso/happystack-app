<?php

declare(strict_types=1);

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained();
            $table->boolean('via_mail')->default(false);
            $table->string('mail_route')->nullable();
            $table->text('mail_message')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->index('notified_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
