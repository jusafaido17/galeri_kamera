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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); // Untuk user yang belum login
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->string('duration'); // 6_hours, 12_hours, 24_hours, 1_5_days
            $table->date('rental_date'); // Tanggal mulai sewa
            $table->dateTime('rental_start'); // Jam mulai sewa
            $table->dateTime('rental_end'); // Jam selesai sewa
            $table->decimal('price', 10, 2); // Harga sesuai durasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
