<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('business_memberships', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('business_id')
                ->constrained('businesses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained('branches')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('status')->default('active');

            $table->timestamp('joined_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'business_id', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_memberships');
    }
};