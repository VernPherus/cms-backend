@extends('layouts.app')

@section('content')
<div class="container pb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url('') }}" class="text-decoration-none text-secondary">
            &larr; Back to Dashboard
        </a>
        
        <div>
            @if($disbursement->status === 'approved')
                <span class="badge bg-success fs-6 px-3 py-2">APPROVED</span>
            @else
                <span class="badge bg-warning text-dark fs-6 px-3 py-2">PENDING</span>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white p-4 border-bottom">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-uppercase text-muted fw-bold">Payee</small>
                    <h3 class="fw-bold text-dark">{{ $disbursement->payee->name }}</h3>
                    <p class="mb-0 text-muted">{{ $disbursement->payee->address }}</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <small class="text-uppercase text-muted fw-bold">Fund Source</small>
                    <h5 class="fw-bold">{{ $disbursement->fundSource->code }}</h5>
                    <p class="mb-0">{{ $disbursement->fundSource->name }}</p>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            
            <div class="bg-light p-3 rounded border mb-4">
                <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Reference Data</h6>
                <div class="row g-3 text-center">
                    <div class="col-6 col-md-2 border-end">
                        <small class="d-block text-muted">ORS #</small>
                        <span class="fw-bold font-monospace">{{ $disbursement->ors_num ?? '---' }}</span>
                    </div>
                    <div class="col-6 col-md-2 border-end">
                        <small class="d-block text-muted">DV #</small>
                        <span class="fw-bold font-monospace">{{ $disbursement->voucher_number ?? '---' }}</span>
                    </div>
                    <div class="col-6 col-md-2 border-end">
                        <small class="d-block text-muted">LDDAP #</small>
                        <span class="fw-bold font-monospace">{{ $disbursement->lddap_num ?? '---' }}</span>
                    </div>
                    <div class="col-6 col-md-2 border-end">
                        <small class="d-block text-muted">ACIC #</small>
                        <span class="fw-bold font-monospace">{{ $disbursement->acic_num ?? '---' }}</span>
                    </div>
                    <div class="col-6 col-md-2 border-end">
                        <small class="d-block text-muted">Method</small>
                        <span class="badge bg-secondary">{{ strtoupper($disbursement->method ?? 'N/A') }}</span>
                    </div>
                    <div class="col-6 col-md-2">
                        <small class="d-block text-muted">Date Received</small>
                        <span class="fw-bold">
                            {{ $disbursement->date_received ? \Carbon\Carbon::parse($disbursement->date_received)->format('M d, Y') : '---' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <h6 class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Particulars</h6>
                <div class="p-3 bg-white border rounded">
                    {{ $disbursement->particulars }}
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7">
                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Disbursement Items</h6>
                    <table class="table table-sm table-borderless">
                        <tbody>
                            @foreach($disbursement->items as $item)
                            <tr>
                                <td>{{ $item->description }}</td>
                                <td class="text-end fw-bold">{{ number_format($item->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-top">
                            <tr>
                                <td class="text-uppercase text-muted pt-2">Total Gross</td>
                                <td class="text-end fw-bold pt-2">{{ number_format($disbursement->gross_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="col-lg-5">
                    <h6 class="text-danger fw-bold mb-3 border-bottom pb-2">Deductions</h6>
                    @if($disbursement->deductions->count() > 0)
                        <table class="table table-sm table-borderless">
                            <tbody>
                                @foreach($disbursement->deductions as $deduction)
                                <tr>
                                    <td>{{ $deduction->deduction_type }}</td>
                                    <td class="text-end text-danger">({{ number_format($deduction->amount, 2) }})</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td class="text-uppercase text-muted pt-2">Total Deductions</td>
                                    <td class="text-end text-danger fw-bold pt-2">({{ number_format($disbursement->total_deductions, 2) }})</td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <p class="text-muted fst-italic small">No deductions recorded.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-footer bg-dark text-white p-4 d-flex justify-content-between align-items-center">
            <span class="fs-5 fw-light">NET AMOUNT PAYABLE</span>
            <span class="fs-2 fw-bold">{{ number_format($disbursement->net_amount, 2) }}</span>
        </div>
    </div>

    <div class="mt-4 text-end">
        @if($disbursement->status !== 'approved')
            <button id="approveBtn" class="btn btn-success btn-lg shadow">
                <i class="bi bi-check-circle"></i> Approve Disbursement
            </button>
        @else
            <button class="btn btn-secondary" disabled>Already Approved</button>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    const approveBtn = document.getElementById('approveBtn');
    if(approveBtn) {
        approveBtn.addEventListener('click', function() {
            if(!confirm('Are you sure you want to approve this disbursement?')) return;

            const id = "{{ $disbursement->id }}";
            this.disabled = true;
            this.innerText = 'Approving...';

            fetch(`/api/disbursements/${id}/approve`, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message || 'Approved Successfully');
                location.reload(); 
            })
            .catch(err => {
                alert('Error approving record');
                console.error(err);
                this.disabled = false;
                this.innerText = 'Approve Disbursement';
            });
        });
    }
</script>
@endpush