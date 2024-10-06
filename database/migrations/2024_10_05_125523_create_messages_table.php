<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('sender_id'); // Sender of the message
            $table->unsignedBigInteger('receiver_id'); // Receiver of the message
            $table->text('content'); // Message content
            $table->timestamp('read_at')->nullable(); // Timestamp for when the message is read
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraints
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
