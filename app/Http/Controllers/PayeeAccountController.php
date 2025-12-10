<?php

namespace App\Http\Controllers;

use App\Models\Disbursement;
use App\Models\Payee;
use App\Models\FundSource;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class PayeeAccountController extends Controller
{

    /**
     * Display list of payees
     */
    public function index()
    {
        // returns payees sorted by name
        $payees = Payee::orderBy('name')->get();

        return response()->json([
            'message' => 'Payees retrieved successfully',
            'data' => $payees
        ], 200);
    }

    /**
     * Create new payee account
     */
    public function store(Request $request)
    {
        // input validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'type' => 'required|string|in:supplier,employee,government,other', // Enforce specific types
        ]);

        // Start transaction
        try {
            return DB::transaction(function () use ($validated){
                $payee = Payee::create([
                    'name' => $validated['name'],
                    'address' => $validated['address'] ?? null,
                    'type' => $validated['type'],
                ]);
                
                return response()->json([
                    'message' => 'New payee account created successfully',
                    'data' => $payee
                ], 201);

            });
            

            return response()->json([
                'message' => 'New payee account created successfully',
                'data' => $payee
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to create payee',
                'details' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show payee account
     */
    public function show($id)
    {
        try {
            $payee = Payee::findOrFail($id);

            return response()->json([
                'message' => 'Payee details retrieved',
                'data' => $payee
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Payee not found'], 404);
        }
    }

    /**
     * Update payee account
     */
    public function update(Request $request, $id)
    {
        try {
            $payee = Payee::findOrFail($id);

            // 1. Input Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|string',
                'type' => 'required|string|in:supplier,employee,government,other',
            ]);

            // 2. Update Record
            $payee->update([
                'name' => $validated['name'],
                'address' => $validated['address'] ?? $payee->address,
                'type' => $validated['type'],
            ]);

            return response()->json([
                'message' => 'Payee account updated successfully',
                'data' => $payee
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Payee not found'], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to update payee',
                'details' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Deactivate payee account
     */
    public function deactivate($id)
    {
        try {
            $payee = Payee::findOrFail($id);

            $payee->delete(); 
            $message = 'Payee moved to trash (Soft Deleted)';

            return response()->json([
                'message' => $message,
                'data' => $payee
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Payee not found'], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to deactivate payee',
                'details' => $th->getMessage()
            ], 500);
        }
    }

}
