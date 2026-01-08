<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {

            // 1. Hapus foreign key lama
            // Kita harus drop foreign key dulu, baru nanti bisa ubah kolom
            if (config('database.default') !== 'sqlite') {
                $table->dropForeign('articles_category_id_foreign');
            }

            if (Schema::hasIndex('articles', 'articles_category_id_foreign')) {
                $table->dropIndex('articles_category_id_foreign');
            }

            // 2. Ubah kolom category_id menjadi nullable
            $table->unsignedBigInteger('category_id')->nullable()->change();

            // 3. Tambahkan foreign key baru dengan SET NULL
            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->nullOnDelete();  // ON DELETE SET NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {

            // Hapus foreign key baru
            $table->dropForeign(['category_id']);

            // Kembalikan jadi NOT NULL
            $table->unsignedBigInteger('category_id')->nullable(false)->change();

            // Tambahkan index lama kembali
            $table->index('category_id', 'articles_category_id_foreign');
        });
    }
};
