<?php

namespace App\Http\Controllers;

use App\Models\Disbursement;
use App\Models\Payee;
use App\Models\FundSource;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FundAccountController extends Controller
{

    /**
     * Display list of fund sources
     */
    public function index()
    {
        // returns fund sources sorted by name
        $fundSource = FundSource::orderBy('name')->get();

        return response()->json([
            'message' => 'Fund Sources retrieved successfully',
            'data' => $fundSource
        ], 200);
    }


    /**
     * Create new fund account
     */
    public function store(Request $requests)
    {
        // input validation
        $validated = $requests->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:fund_sources,code|max:50',
            'description' => 'nullable|string',
        ]);

        try {
            
            // Start transaction
            return DB::transaction(function () use ($validated){
                $fundSource = FundSource::create([
                    'name' => $validated['name'],
                    'code' => $validated['code'],
                    'description' => $validated['description'] ?? null,
                    'is_active' => true,
                ]);

                // Return results
                return response() -> json ([
                    'message' => 'New fund source created successfully', 
                    'data' => $fundSource
                ], 201);
            });
            
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to create account: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show fund account
     */
    public function show($id)
    {
        $fund = FundSource::findOrFail($id);
        return response() -> json($fund);
    }

    /**
     * Update fund account
     */
    public function update(Request $request, $id)
    {
        $fundSource = FundSource::findOrFail($id);

        // input validation
        $validated = $requests->validate([
            'name' => 'required|string|max:255',
            // Unique check ignores the current ID (so you can update name without changing code)
            'code' => 'required|string|max:50|unique:fund_sources,code,' . $id,
            'description' => 'nullable|string',
        ]);

        try {
            $fundSource->update([
                'name'->$validated['name'],
                'code'->$validated['code'],
                'description'->$validated['description'] ?? null,
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to update: ' . $th->getMessage()]);
        }

    }

    /**
     * Deactivate fund account
     */
    public function deactivate()
    {
        try {
            $fundSource = FundSource::findOrFail($id);

            $fundSource->update(['is_active' => false]);

            return response()->json(['message'=>'Fund source deactivated', 'data'=>$fundSource], 200);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to deactivate: ' . $th->getMessage()]);
        }
    }

    /**
     * Optional: Reactivate Route
     */
    public function reactivate($id)
    {
        $fundSource = FundSource::findOrFail($id);
        $fundSource->update(['is_active' => true]);
        
        return back()->with('success', 'Fund source reactivated');
    }
}
