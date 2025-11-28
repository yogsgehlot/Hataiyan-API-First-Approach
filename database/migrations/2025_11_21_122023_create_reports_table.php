<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // who submitted report
            $table->unsignedBigInteger('reported_by');

            // target details (can be user/post/comment)
            $table->unsignedBigInteger('reported_user_id')->nullable();
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedBigInteger('comment_id')->nullable();

            // reason & status
            $table->string('reason')->nullable();
            $table->text('details')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending');

            // admin who processed (optional)
            $table->unsignedBigInteger('processed_by')->nullable();

            $table->timestamps();

            // foreign keys
            $table->foreign('reported_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reported_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
