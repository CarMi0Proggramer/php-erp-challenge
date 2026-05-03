<?php

use App\Enums\StockMovementType;
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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', StockMovementType::cases());
            $table->integer('stock_before');
            $table->integer('stock_balance');
            $table->integer('quantity')->nullable();
            $table->string('reason')->nullable();

            $table->foreignUuid('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
