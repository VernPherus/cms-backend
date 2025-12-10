<?php

namespace App\Http\Controllers\Admin;

use App\Models\Disbursement;
use App\Models\Payee;
use App\Models\FundSource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DisbursementAdminController extends Controller
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
        $payees = Payee::orderBy('name')->get();
        $fundSources = FundSource::where('is_active', true)->get();

        return view('disbursements.create', compact('payees', 'fundSources'));
    }

    /**
     * CREATE FUNDS: Loads fund creation form
     */
    public function fundform()
    {
        return view('funds.create');
    }

    /**
     * CREATE PAYEE: Loads payee account creation form
     */
    public function payeeform()
    {
        return view('payees.create');
    }


}
