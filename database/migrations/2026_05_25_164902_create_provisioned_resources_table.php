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
        Schema::create('provisioned_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->enum('resource_type', ['compute', 'storage', 'network']);
            $table->string('instance_name');
            $table->string('ministack_resource_id');
            $table->json('configuration');
            $table->enum('status', ['running', 'stopped', 'terminated']);
            $table->decimal('hourly_cost', 10, 4);
            $table->timestamp('rent_start_date');
            $table->timestamp('rent_end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provisioned_resources');
    }
};
