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
        Schema::create('payees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('');
            $table->timestamps();
        });

        Schema::create('disbursements', function (Blueprint $table){
            $table->id();

            //* Foreign key to payee
            $table->foreignId('payee_id')->constrained('payees')->onDelete('cascade');

            //* Document References
            // indexed for fast search
            $table->string('lddap_num')->nullable()->index(); 
            $table->string('acic_num')->nullable()->index();
            $table->string('ors_num')->nullable()->index();
            $table->string('dv_num')->nullable()->index();
            $table->string('uacs_code')->nullable()->index();
            $table->string('resp_code')->nullable()->index();

            //* Dates and Timestamps
            $table->timestamp('date_received')->nullable(); // When the bill/request was physically received
            $table->timestamp('date_entered')->useCurrent(); // When it was encoded

            //* Financials
            $table->decimal('gross_amount', 15, 2);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);

            //* Details

            //* Status
            $table->string('status')->default('pending')->index();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes(); // for record restoration if required
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payees');
    }
};
