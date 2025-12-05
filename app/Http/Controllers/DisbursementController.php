<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DisbursementController
{
    /** 
     * STORE: Create a new disbursement, its items, and deductions. 
     */ 

    public function store(Request $requests)
    {
        // input validation
        $validated = $requests->validate([
            'payee_id' => 'required|exists:payees,id',
            'fund_source_id' => 'required|exists:fund_sources,id',
            'check_number' => 'nullable|string',
            'voucher_number' => 'nullable|string',
            'date_received' => 'nullable|date',
            'purpose' => 'required|string',
            
            // Array inputs for Items
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.amount' => 'required|numeric|min:0',
            
            // Array inputs for Deductions (Optional)
            'deductions' => 'nullable|array',
            'deductions.*.deduction_type' => 'required|string',
        ]);

        try {
            // Start transaction
            return DB::transaction(function () use ($validated) {
                
                // Create the Header (Initially with 0 totals)
                $disbursement = Disbursement::create([
                    'payee_id' => $validated['payee_id'],
                    'fund_source_id' => $validated['fund_source_id'],
                    'check_number' => $validated['check_number'] ?? null,
                    'voucher_number' => $validated['voucher_number'] ?? null,
                    'date_received' => $validated['date_received'] ?? null,
                    'date_entered' => now(),
                    'purpose' => $validated['purpose'],
                    'status' => 'pending',
                    'gross_amount' => 0, 
                    'total_deductions' => 0,
                    'net_amount' => 0,
                ]);

                // Create Items & Calculate Gross
                $grossAmount = 0;
                foreach ($validated['items'] as $item) {
                    $disbursement->items()->create([
                        'description' => $item['description'],
                        'amount' => $item['amount'],
                        // 'account_code' => $item['account_code'] ?? null,
                    ]);
                    $grossAmount += $item['amount'];
                }

                // Create Deductions & Calculate Total Deductions
                $totalDeductions = 0;
                if (!empty($validated['deductions'])) {
                    foreach ($validated['deductions'] as $deduction) {
                        $disbursement->deductions()->create([
                            'deduction_type' => $deduction['deduction_type'],
                            'amount' => $deduction['amount'],
                        ]);
                        $totalDeductions += $deduction['amount'];
                    }
                }

                // Update Header with Final Calculations
                $disbursement->update([
                    'gross_amount' => $grossAmount,
                    'total_deductions' => $totalDeductions,
                    'net_amount' => $grossAmount - $totalDeductions,
                ]);

                return response()->json([
                    'message' => 'Disbursement created successfully',
                    'data' => $disbursement->load(['items', 'deductions'])
                ], 201);
            });
                
        } catch (\Throwable $th) {
            // If anything fails above, nothing is saved to the DB.
            return response()->json(['error' => 'Failed to create record: ' . $e->getMessage()], 500);
        }
    }
    

    /**
     * Display a single record with all its details
     */
    public function show($id)
    {
        $disbursement = Disbursement::with(['payee', 'fundSource', 'items', 'deductions'])->findOrFail($id);
        return response()->json($disbursement);    
    }

    /** 
     * UPDATE: Edit an existing record
     */
    public function update(Request $request, $id)
    {
        
    }

    //* Approve Record

    //* Delete Record
}
