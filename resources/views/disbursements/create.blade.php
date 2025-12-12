@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-white sidebar shadow-sm collapse" style="min-height: 100vh;">
            <div class="position-sticky pt-4 px-3">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-2 mb-3 text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                    <span>Management</span>
                </h6>
                <ul class="nav flex-column mb-4">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-secondary d-flex align-items-center gap-2 p-2 rounded hover-bg-light" href="{{ route('disbursementadmin.index') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link active d-flex align-items-center gap-2 p-2 rounded bg-light text-primary fw-bold" href="#">
                            <i class="bi bi-plus-circle"></i> New Disbursement
                        </a>
                    </li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-3 text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                    <span>Administration</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item mb-1">
                        <a class="nav-link text-secondary d-flex align-items-center gap-2 p-2 rounded hover-bg-light" href="{{ route('disbursementadmin.fundform') }}">
                            <i class="bi bi-bank"></i> Add Fund Source
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link text-secondary d-flex align-items-center gap-2 p-2 rounded hover-bg-light" href="{{ route('disbursementadmin.payeeform') }}">
                            <i class="bi bi-people"></i> Add Payee
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 bg-light">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-0">New Entry</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('disbursementadmin.index') }}" class="text-decoration-none">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Disbursement</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-file-earmark-text me-2"></i>Transaction Details</h5>
                </div>
                
                <div class="card-body p-4">
                    <form id="disbursementForm">
                        @csrf

                        <!-- GENERAL INFORMATION -->
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">General Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Payee</label>
                                <select name="payee_id" class="form-select" required>
                                    <option value="">Select Payee...</option>
                                    @foreach ($payees as $payee)
                                        <option value="{{ $payee->id }}">{{ $payee->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fund Source</label>
                                <select name="fund_source_id" class="form-select" required>
                                    <option value="">Select Fund...</option>
                                    @foreach ($fundSources as $fund)
                                        <option value="{{ $fund->id }}">{{ $fund->code }} - {{ $fund->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date Received</label>
                                <input type="date" name="date_received" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label d-block">Payment Method</label>
                                <div class="btn-group w-100 w-md-auto" role="group">
                                    <input type="radio" class="btn-check" name="method" id="methodManual" value="manual">
                                    <label class="btn btn-outline-secondary" for="methodManual">Manual</label>

                                    <input type="radio" class="btn-check" name="method" id="methodOnline" value="online" checked>
                                    <label class="btn btn-outline-secondary" for="methodOnline">Online</label>
                                </div>
                            </div>
                        </div>

                        <!-- DOCUMENT REFERENCES -->
                        <div class="bg-light p-4 rounded border mb-4">
                            <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">Document References</h6>

                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">ORS / BURs No.</label>
                                    <input type="text" name="ors_num" class="form-control form-control-sm font-monospace bg-white">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">DV Number</label>
                                    <input type="text" name="dv_num" class="form-control form-control-sm font-monospace bg-white">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">LDDAP No.</label>
                                    <input type="text" name="lddap_num" class="form-control form-control-sm font-monospace bg-white">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">ACIC Number</label>
                                    <input type="text" name="acic_num" class="form-control form-control-sm font-monospace bg-white">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">UACS Code</label>
                                    <input type="text" name="uacs_code" class="form-control form-control-sm font-monospace bg-white">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Responsibility Code</label>
                                    <input type="text" name="resp_code" class="form-control form-control-sm font-monospace bg-white">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Particulars / Purpose</label>
                            <textarea name="particulars" class="form-control" rows="3" placeholder="Enter description of payment..." required></textarea>
                        </div>

                        <hr class="my-4 text-muted">

                        <!-- DISBURSEMENT ITEMS -->
                        <div class="row">
                            <div class="col-xl-7 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-primary fw-bold mb-0">Disbursement Items (Gross)</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItemRow()">+ Add Item</button>
                                </div>
                                <div class="table-responsive border rounded">
                                    <table class="table table-sm table-striped mb-0" id="items_table">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Description</th>
                                                <th width="30%">Amount</th>
                                                <th width="40px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="ps-3"><input type="text" name="items[0][description]" class="form-control form-control-sm border-0 bg-transparent" placeholder="Item Name" required></td>
                                                <td><input type="number" step="0.01" name="items[0][amount]" class="form-control form-control-sm border-0 bg-transparent" placeholder="0.00" required></td>
                                                <td class="text-center"><button type="button" class="btn btn-danger btn-sm py-0 rounded-circle" onclick="removeRow(this)">&times;</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- DEDUCTIONS -->
                            <div class="col-xl-5 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-danger fw-bold mb-0">Deductions</h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="addDeductionRow()">+ Add Deduction</button>
                                </div>
                                <div class="table-responsive border rounded">
                                    <table class="table table-sm table-striped mb-0" id="deductions_table">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Type</th>
                                                <th width="35%">Amount</th>
                                                <th width="40px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end pt-3">
                            <a href="{{ route('disbursementadmin.index') }}" class="btn btn-light border me-2">Cancel</a>
                            <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">Save Disbursement Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    /* Styling for the sidebar hover states */
    .hover-bg-light:hover {
        background-color: #f8f9fa !important;
        color: #0d6efd !important;
        transition: all 0.2s;
    }
    .nav-link {
        color: #6c757d;
        font-weight: 500;
    }
</style>
@endsection

@push('scripts')
    <script>
        /**
         * Handle form submission
        */
        document.getElementById('disbursementForm').addEventListener('submit', function(e) {
            e.preventDefault(); 

            let formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');

            submitBtn.disabled = true;
            submitBtn.innerText = 'Saving...';

            fetch('/api/disbursements', { 
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 201 || status === 200) {
                        alert('Success: ' + body.message);
                        window.location.href = '/disbursementadmin'; 
                    }
                    else if (status === 422) {
                        let errorMsg = 'Validation Failed:\n';
                        for (const [key, messages] of Object.entries(body.errors)) {
                            errorMsg += `• ${messages[0]}\n`;
                        }
                        alert(errorMsg);
                        submitBtn.disabled = false;
                        submitBtn.innerText = 'Save Record';
                    }
                    else {
                        alert('Error: ' + (body.error || body.message || 'Something went wrong'));
                        submitBtn.disabled = false;
                        submitBtn.innerText = 'Save Record';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Network error occurred.');
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Save Record';
                });
        });

        /**
         * Handle item and deductions row 
        */
        let itemIndex = 1;
        let deductionIndex = 0;

        function addItemRow() {
            const table = document.getElementById('items_table').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();
            newRow.innerHTML = `
            <td class="ps-3"><input type="text" name="items[${itemIndex}][description]" class="form-control form-control-sm border-0 bg-transparent" placeholder="Item Name" required></td>
            <td><input type="number" step="0.01" name="items[${itemIndex}][amount]" class="form-control form-control-sm border-0 bg-transparent" placeholder="0.00" required></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm py-0 rounded-circle" onclick="removeRow(this)">&times;</button></td>
        `;
            itemIndex++;
        }

        function addDeductionRow() {
            const table = document.getElementById('deductions_table').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();
            newRow.innerHTML = `
            <td class="ps-3"><input type="text" name="deductions[${deductionIndex}][deduction_type]" class="form-control form-control-sm border-0 bg-transparent" placeholder="Deduction Type" required></td>
            <td><input type="number" step="0.01" name="deductions[${deductionIndex}][amount]" class="form-control form-control-sm border-0 bg-transparent" placeholder="0.00" required></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm py-0 rounded-circle" onclick="removeRow(this)">&times;</button></td>
        `;
            deductionIndex++;
        }

        function removeRow(btn) {
            const row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    </script>
@endpush