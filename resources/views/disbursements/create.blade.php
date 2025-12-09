@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10"> <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white"><i class="bi bi-file-earmark-text"></i> Create New Disbursement</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('disbursements.store') }}" method="POST">
                        @csrf

                        <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">Transaction Details</h6>
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
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="method" id="method" value="manual">
                                    <label class="btn btn-outline-secondary" for="methodManual">Manual</label>
                                  
                                    <input type="radio" class="btn-check" name="method" id="method" value="online" checked>
                                    <label class="btn btn-outline-secondary" for="methodOnline">Online</label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded border mb-4">
                            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">Document References</h6>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">ORS / BURs No.</label>
                                    <input type="text" name="ors_num" class="form-control form-control-sm font-monospace">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">DV Number</label>
                                    <input type="text" name="dv_num" class="form-control form-control-sm font-monospace">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">LDDAP No.</label>
                                    <input type="text" name="lddap_num" class="form-control form-control-sm font-monospace">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">ACIC Number</label>
                                    <input type="text" name="acic_num" class="form-control form-control-sm font-monospace">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">UACS Code</label>
                                    <input type="text" name="uacs_code" class="form-control form-control-sm font-monospace">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Responsibility Code</label>
                                    <input type="text" name="resp_code" class="form-control form-control-sm font-monospace">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Particulars / Purpose</label>
                            <textarea name="particulars" class="form-control" rows="3" placeholder="Enter description of payment..." required></textarea>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-lg-7 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-primary fw-bold mb-0">Disbursement Items (Gross)</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItemRow()">+ Add Item</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" id="items_table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Description</th>
                                                <th width="30%">Amount</th>
                                                <th width="40px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" name="items[0][description]" class="form-control form-control-sm" required></td>
                                                <td><input type="number" step="0.01" name="items[0][amount]" class="form-control form-control-sm" placeholder="0.00" required></td>
                                                <td class="text-center"><button type="button" class="btn btn-danger btn-sm py-0" onclick="removeRow(this)">&times;</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-5 mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-danger fw-bold mb-0">Deductions</h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="addDeductionRow()">+ Add Deduction</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm" id="deductions_table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Type</th>
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

                        <div class="card-footer bg-white border-top-0 d-flex justify-content-end pt-3">
                            <a href="{{ route('disbursementadmin.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-success px-4 fw-bold">Save Disbursement Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let itemIndex = 1;
        let deductionIndex = 0;

        function addItemRow() {
            const table = document.getElementById('items_table').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();
            newRow.innerHTML = `
            <td><input type="text" name="items[${itemIndex}][description]" class="form-control form-control-sm" required></td>
            <td><input type="number" step="0.01" name="items[${itemIndex}][amount]" class="form-control form-control-sm" required></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm py-0" onclick="removeRow(this)">&times;</button></td>
        `;
            itemIndex++;
        }

        function addDeductionRow() {
            const table = document.getElementById('deductions_table').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();
            newRow.innerHTML = `
            <td><input type="text" name="deductions[${deductionIndex}][deduction_type]" class="form-control form-control-sm" required></td>
            <td><input type="number" step="0.01" name="deductions[${deductionIndex}][amount]" class="form-control form-control-sm" required></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm py-0" onclick="removeRow(this)">&times;</button></td>
        `;
            deductionIndex++;
        }

        function removeRow(btn) {
            const row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    </script>
@endpush