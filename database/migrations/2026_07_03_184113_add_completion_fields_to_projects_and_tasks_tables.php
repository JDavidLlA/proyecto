<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'completed_by')
                ->nullable()
                ->after('due_date')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('completed_at')
                ->nullable()
                ->after('completed_by');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'completed_by')
                ->nullable()
                ->after('prioridad')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('completed_at')
                ->nullable()
                ->after('completed_by');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('completed_by');
            $table->dropColumn('completed_at');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('completed_by');
            $table->dropColumn('completed_at');
        });
    }
};