<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transaction_id')
                ->constrained('transactions')
                ->cascadeOnDelete();

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->restrictOnDelete();

            $table->string('side'); // debit | credit
            $table->decimal('amount', 18, 2);

            $table->timestamps();

            $table->index('transaction_id');
            $table->index('account_id');
            $table->index('side');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
