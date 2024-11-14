<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->string('package', 255);
            $table->string('image_path', 255);
            $table->integer('qty');
            $table->date('end_date')->nullable();
            $table->decimal('price', 65, 2);
            $table->decimal('grand_price', 65, 2);
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
