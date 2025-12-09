<?php

namespace App\Http\Controllers;

use App\Models\Disbursement;
use App\Models\Payees;
use App\Models\FundSource;
use Illuminate\HTTP\Requests;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

class DisbursementController
{

    /**
     * *TEST DISPLAY CODE:
     */

    /**
     * INDEX: Loads the dashboard
     */
    public function index()
    {
        // Get records, ordered by newest first, with pagination
        $disbursements = Disbursement::with(['payee', 'fundSource'])
                            ->latest('date_entered')
                            ->paginate(10);

        return view('disbursements.dashboard', compact('disbursements'));
    }

    /**
     * CREATE: Loads the Form
     */
    public function create()
    {
        // We need these lists to populate the <select> dropdowns
        $payees = Payees::orderBy('name')->get();
        $fundSources = FundSource::where('is_active', true)->get();

        return view('disbursements.create', compact('payees', 'fundSources'));
    }

    
    /** 
     * *STORE: Create a new disbursement, its items, and deductions. 
     */ 

    public function store(Request $requests)
    {
        // input validation
        $validated = $requests->validate([
            'payee_id' => 'required|exists:payees,id',
            'fund_source_id' => 'required|exists:fund_sources,id',
            'date_received' => 'nullable|date',

            //* Array inputs for Document references
            'lddap_num' => 'nullable|string',
            'acic_num' => 'nullable|string',
            'ors_num' => 'nullable|string',
            'dv_num' => 'nullable|string',
            'uacs_code' => 'nullable|string',
            'resp_code' => 'nullable|string',

            //* Array inputs for Details
            'particulars' => 'nullable|string',
            'methods' => 'required',
            
            //* Array inputs for Items
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.amount' => 'required|numeric|min:0',
            
            //* Array inputs for Deductions (Optional)
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
     * *Display a single record with all its details
     */
    public function show($id)
    {
        $disbursement = Disbursement::with(['payee', 'fundSource', 'items', 'deductions'])->findOrFail($id);
        return response()->json($disbursement);    
    }

    /** 
     * *UPDATE: Edit an existing record
     * Strat: Wipe out existing items/deductions and recreate them.
     * Safer than trying to match IDs for edits in a financial context.
     */
    public function update(Request $request, $id)
    {
        $disbursement = Disbursement::findOrFail($id);

        // Prevent editing if already approved
        if ($disbursement->status==='approved') {
            return response()-> json(['error'=>'Cannot edit an approved disbursement'], 403);
        }

        // Validate, same rules as store
        $validated = $requests->validate([
            'payee_id' => 'required|exists:payees,id',
            'fund_source_id' => 'required|exists:fund_sources,id',
            'date_received' => 'nullable|date',

            //* Array inputs for Document references
            'lddap_num' => 'nullable|string',
            'acic_num' => 'nullable|string',
            'ors_num' => 'nullable|string',
            'dv_num' => 'nullable|string',
            'uacs_code' => 'nullable|string',
            'resp_code' => 'nullable|string',

            //* Array inputs for Details
            'particulars' => 'nullable|string',
            'methods' => 'required',
            
            //* Array inputs for Items
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.amount' => 'required|numeric|min:0',
            
            //* Array inputs for Deductions (Optional)
            'deductions' => 'nullable|array',
            'deductions.*.deduction_type' => 'required|string',
        ]);

        return DB::transaction(function () use ($disbursement, $validated){
            
            // Update header info
            $disbursement->update([
                'payee_id'=> $validated['payee_id'],
                'fund_source_id'=> $validated['fund_source_id'],
                'particulars' => $validated['particulars'],

                //* Document references
                'lddap_num' => $validated['lddap_num'],
                'acic_num' => $validated['acic_num'],
                'ors_num' => $validated['ors_num'],
                'dv_num' => $validated['dv_num'],
                'uacs_code' => $validated['uacs_code'],
                'resp_code' => $validated['resp_code'],
            ]);

            // Delete old children 
            $disbursement->items()->delete();
            $disbursement->deductions()->delete();

            // Re-create items
            $grossAmount = 0;
            foreach($validated['items'] as $item){
                $disbursement->items()->create($item);
                $grossAmount += $item['amount'];
            }

            // Re-create Deductions
            $totalDeductions = 0;
            if(!empty($validated['deductions']))
            {
                foreach($validated['deductions']as $deduction)
                {
                    $disbursement->deductions()->create($deduction);
                    $totalDeductions += $deduction['amount'];
                }            
            }

            // Update totals
            $disbursement->update([
                'gross_amount' => $grossAmount,
                'total_deductions' => $totalDeductions,
                'net_amount'=> $grossAmount - $totalDeductions,
            ]);

            return response()->json(['message'=>'Disbursement updated', 'data'=> $disbursement], 200);

        });

    }

    /**
     * *Approve Record
     */
    public function approve($id)
    {
        $disbursement = Disbursement::findOrFail($id);

        $disbursement->update([
            'status'=>'approved',
            'approved_at' => now(),
        ]);

        return response()->json(['message' => 'Disbursement approved successfully'], 200);
    }

    /**
     * *Delete Record 
     */
    public function delete()
    {
        $disbursement = Disbursement::findOrFail($id);

        //* OPTIONAL: Check if approved before deleting
        if($disbursement->status === 'approved')
        {
            // Depending on policy, you might block deletion of approved records
            // return response()->json(['error' => 'Cannot delete approved records'], 403);
            pass;
        }

        $disbursement->delete();

        return response()->json(['message'=>'Disbursement moved to trash'], 200);

    }
}
