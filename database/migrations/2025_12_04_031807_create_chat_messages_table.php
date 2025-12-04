<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('session_id')->nullable();
            $table->text('message');
            $table->text('response');
            $table->json('context')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('customer_id')
                ->references('id_customer')
                ->on('customers')
                ->onDelete('cascade');

            // Indexes
            $table->index('customer_id');
            $table->index('session_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
