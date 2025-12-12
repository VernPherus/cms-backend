@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white"><i class="bi bi-bank"></i> Create New Fund Source</h5>
                </div>
                <div class="card-body p-4">
                    <form id="fundForm">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fund Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., General Fund"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Fund Code</label>
                            <input type="text" name="code" class="form-control font-monospace"
                                placeholder="e.g., GF-101" required>
                            <div class="form-text">Must be unique.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Initial Balance</label>
                            <input type="number" step="0.01" min="0" name="initial_balance" class="form-control" placeholder="0.0"
                                required>
                        </div>


                        <div class="mb-4">
                            <label class="form-label fw-bold">Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ url('/funds') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-success px-4">Save Fund</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('fundForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = 'Saving...';

            fetch('/api/funds', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: new FormData(this)
                })
                .then(res => res.json().then(data => ({
                    status: res.status,
                    body: data
                })))
                .then(({
                    status,
                    body
                }) => {
                    if (status === 201) {
                        alert('Success: ' + body.message);
                        window.location.href = '/'; // Update this URL to your actual funds list route
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
