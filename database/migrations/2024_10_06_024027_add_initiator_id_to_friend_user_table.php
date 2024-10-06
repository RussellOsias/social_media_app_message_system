<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('friend_user', function (Blueprint $table) {
            // Use a different name for the unique constraint
            $table->unique(['user_id', 'friend_id'], 'unique_user_friend');
        });
    }
    
    
    public function down()
    {
        Schema::table('friend_user', function (Blueprint $table) {
            $table->dropColumn('initiator_id');
        });
    }
    

    /**
     * Reverse the migrations.
     */
  
};
