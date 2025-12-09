@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-white">
                <h4 class="mb-0">Create New Disbursement</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('disbursements.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Payee</label>
                            <select name="payee_id" class="form-select" required>
                                <option value="">Select Payee...</option>
                                @foreach($payees as $payee)
                                    <option value="{{ $payee->id }}">{{ $payee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fund Source</label>
                            <select name="fund_source_id" class="form-select" required>
                                <option value="">Select Fund...</option>
                                @foreach($fundSources as $fund)
                                    <option value="{{ $fund->id }}">{{ $fund->code }} - {{ $fund->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Check Number</label>
                            <input type="text" name="check_number" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Voucher #</label>
                            <input type="text" name="voucher_number" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date Received</label>
                            <input type="date" name="date_received" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Purpose</label>
                            <textarea name="purpose" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5 class="text-primary">Items (Gross)</h5>
                        <table class="table table-bordered" id="items_table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th width="25%">Amount</th>
                                    <th width="50px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="items[0][description]" class="form-control" required></td>
                                    <td><input type="number" step="0.01" name="items[0][amount]" class="form-control" placeholder="0.00" required></td>
                                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItemRow()">+ Add Item</button>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5 class="text-danger">Deductions</h5>
                        <table class="table table-bordered" id="deductions_table">
                            <thead>
                                <tr>
                                    <th>Deduction Type</th>
                                    <th width="25%">Amount</th>
                                    <th width="50px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="addDeductionRow()">+ Add Deduction</button>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-success btn-lg">Save Record</button>
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
            <td><input type="text" name="items[${itemIndex}][description]" class="form-control" required></td>
            <td><input type="number" step="0.01" name="items[${itemIndex}][amount]" class="form-control" required></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>
        `;
        itemIndex++;
    }

    function addDeductionRow() {
        const table = document.getElementById('deductions_table').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        newRow.innerHTML = `
            <td><input type="text" name="deductions[${deductionIndex}][deduction_type]" class="form-control" required></td>
            <td><input type="number" step="0.01" name="deductions[${deductionIndex}][amount]" class="form-control" required></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>
        `;
        deductionIndex++;
    }

    function removeRow(btn) {
        const row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
</script>
@endpush