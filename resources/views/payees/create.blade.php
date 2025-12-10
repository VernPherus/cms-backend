@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white"><i class="bi bi-person-badge"></i> Create New Payee</h5>
            </div>
            <div class="card-body p-4">
                <form id="payeeForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Payee Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Company or Individual Name" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="supplier">Supplier</option>
                                <option value="employee">Employee</option>
                                <option value="government">Government</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Full Address">
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ url('/payees') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-success px-4">Save Payee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('payeeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true; btn.innerText = 'Saving...';

        fetch('/api/payees', {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: new FormData(this)
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            if (status === 201) {
                alert('Success: ' + body.message);
                window.location.href = '/payees'; // Update to your payees list route
            } else {
                alert('Error: ' + (body.message || JSON.stringify(body.errors)));
                btn.disabled = false; btn.innerText = 'Save Payee';
            }
        });
    });
</script>
@endpush