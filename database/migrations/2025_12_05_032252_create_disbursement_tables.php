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
        //* Payees table
        Schema::create('payees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('type')->default('supplier')->nullable();
            $table->timestamps();
        });

        //* Fund Sources
        Schema::create('fund_sources', function (Blueprint $table){
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        //* Disbursements
        Schema::create('disbursements', function (Blueprint $table){
            $table->id();

            //* Foreign key to payee
            $table->foreignId('payee_id')->constrained('payees')->onDelete('cascade');
            $table->foreignId('fund_source_id')->constrained('fund_sources');

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
            $table->text('particulars');
            $table->text('method')->index(); // Manual or online

            //* Status
            $table->string('status')->default('pending')->index();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes(); // for record restoration if required
        });

        //* Disbursement Items table
        Schema::create('disbursement_items', function (Blueprint $table) {
            $table->id();
            
            // Link to main disbursement
            $table->foreignId('disbursement_id')->constrained('disbursements')->onDelete('cascade');
            
            // Item Details
            $table->string('description'); // e.g., "Inv#101 - Catering Services"
            $table->string('account_code')->nullable(); // Optional: For accounting (e.g., "5-02-05-030")
            $table->decimal('amount', 15, 2); // The cost of this specific item
            
            $table->timestamps();
        });

        //* Disbursement Deductions
        Schema::create('disbursement_deductions', function (Blueprint $table) {
            $table->id();
            
            // Link to the main disbursement
            $table->foreignId('disbursement_id')->constrained('disbursements')->onDelete('cascade');
            
            // Description of deduction
            $table->string('deduction_type'); 
            $table->decimal('amount', 15, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payees');
        Schema::dropIfExists('disbursements');
        Schema::dropIfExists('disbursement_items');
        Schema::dropIfExists('disbursement_deductions');
    }
};
