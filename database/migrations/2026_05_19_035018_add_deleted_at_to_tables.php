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
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        // products
        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        // transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        // transaction_details
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // users
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // products
        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // transaction_details
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
