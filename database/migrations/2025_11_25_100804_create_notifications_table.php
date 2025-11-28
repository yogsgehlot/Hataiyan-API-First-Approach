<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            // polymorphic notifiable (target of the notification): User or Admin
            $table->string('notifiable_type'); // 'App\Models\User' or 'App\Models\Admin'
            $table->unsignedBigInteger('notifiable_id');

            // actor (who caused the notification) -> could be user or admin
            $table->string('actor_type')->nullable(); // 'App\Models\User' or 'App\Models\Admin'
            $table->unsignedBigInteger('actor_id')->nullable();

            $table->string('type'); // e.g. 'post_liked', 'comment', 'mention', 'admin_trashed', 'report_created', 'report_actioned'
            $table->json('data')->nullable(); // event-specific payload (post_id, comment_id, excerpt, etc)

            $table->boolean('is_read')->default(false);

            $table->timestamps();

            // indexes for queries
            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index(['is_read']);
            $table->index(['actor_type', 'actor_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
