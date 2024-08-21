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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('payment_type_id')->nullable()->constrained('payment_types');
            $table->foreignId('menu_id')->nullable()->constrained('menus');
            $table->foreignId('spicy_level_id')->nullable()->constrained('spicy_levels');
            $table->foreignId('ahtone_level_id')->nullable()->constrained('ahtone_levels');
            $table->text('remark')->nullable();
            $table->string('order_no')->nullable();
            $table->string('table_number')->nullable();
            $table->boolean('dine_in_or_percel')->default(0);
            $table->bigInteger('sub_total')->nullable();
            $table->bigInteger('tax')->nullable();
            $table->bigInteger('discount')->nullable();
            $table->bigInteger('grand_total')->nullable();
            $table->bigInteger('paid_cash')->nullable();
            $table->bigInteger('paid_online')->nullable();
            $table->bigInteger('refund')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};