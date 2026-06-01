<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_type_id');
            $table->unsignedBigInteger('parent_account_id')->nullable();
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_active');
            $table->timestamps();
            $table->foreign('account_type_id')->references('id')->on('account_types');
            $table->index('parent_account_id');
            $table->index(['account_type_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
