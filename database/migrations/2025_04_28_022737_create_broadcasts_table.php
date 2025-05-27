<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('broadcasts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->foreignId('sender_id')->constrained('users');
            $table->enum('status', ['draft', 'sent'])->default('draft');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->timestamp('sent_at')->nullable();
            $table->boolean('send_to_all')->default(false);
            $table->timestamps();
        });

        Schema::create('broadcast_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('broadcast_recipients');
        Schema::dropIfExists('broadcasts');
    }
};
