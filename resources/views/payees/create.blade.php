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
                        <a class="nav-link text-secondary d-flex align-items-center gap-2 p-2 rounded hover-bg-light" href="{{ route('disbursementadmin.create') }}">
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
                        <a class="nav-link active d-flex align-items-center gap-2 p-2 rounded bg-light text-primary fw-bold" href="#">
                            <i class="bi bi-people"></i> Add Payee
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 bg-light">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-0">Payee Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('disbursementadmin.index') }}" class="text-decoration-none">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Payee</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-person-badge me-2"></i>Create New Payee</h5>
                        </div>
                        
                        <div class="card-body p-4">
                            <form id="payeeForm">
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Payee Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Company or Individual Name" required>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Type</label>
                                        <select name="type" class="form-select" required>
                                            <option value="" selected disabled>Select Type...</option>
                                            <option value="supplier">Supplier</option>
                                            <option value="employee">Employee</option>
                                            <option value="government">Government Agency</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">TIN Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-muted"><i class="bi bi-card-heading"></i></span>
                                            <input type="text" name="tin_number" class="form-control font-monospace" placeholder="000-000-000-000">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Complete Address</label>
                                    <input type="text" name="address" class="form-control" placeholder="Building, Street, City, Province">
                                </div>

                                <hr class="my-4 text-muted">

                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('disbursementadmin.index') }}" class="btn btn-light border me-2">Cancel</a>
                                    <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">Save Payee Record</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    /* Sidebar Hover Effects */
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
    document.getElementById('payeeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerText;
        
        btn.disabled = true; 
        btn.innerText = 'Saving...';

        fetch('/api/payees', {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: new FormData(this)
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            if (status === 201) {
                alert('Success: ' + body.message);
                window.location.href = '/disbursementadmin'; // Redirect to dashboard
            } else {
                let errorMsg = body.message || body.error;
                if (!errorMsg && body.errors) {
                    errorMsg = JSON.stringify(body.errors);
                }
                alert('Error: ' + (errorMsg || 'Unknown error occurred'));
                
                btn.disabled = false; 
                btn.innerText = originalText;
            }
        })
        .catch(error => {
            console.error('Network Error:', error);
            alert('A network error occurred. Please check your connection.');
            btn.disabled = false;
            btn.innerText = originalText;
        });
    });
</script>
@endpush