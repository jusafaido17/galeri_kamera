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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nama kamera/alat
            $table->string('slug')->unique();
            $table->text('description'); // Deskripsi detail
            $table->text('specifications')->nullable(); // Spesifikasi (JSON format)
            $table->string('image')->nullable(); // Foto utama
            $table->integer('stock'); // Jumlah stok
            $table->decimal('price_6_hours', 10, 2); // Harga sewa 6 jam
            $table->decimal('price_12_hours', 10, 2); // Harga sewa 12 jam
            $table->decimal('price_24_hours', 10, 2); // Harga sewa 24 jam
            $table->decimal('price_1_5_days', 10, 2); // Harga sewa 1.5 hari
            $table->boolean('is_available')->default(true); // Status ketersediaan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
