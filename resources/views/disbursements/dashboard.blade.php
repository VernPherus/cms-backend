@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Disbursement Monitoring</h2>
    <a href="{{ route('disbursements.create') }}" class="btn btn-primary">+ New Entry</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Check #</th>
                    <th>Payee</th>
                    <th>Fund</th>
                    <th class="text-end">Net Amount</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($disbursements as $record)
                <tr>
                    <td>{{ $record->created_at->format('M d, Y') }}</td>
                    <td>
                        <strong>{{ $record->check_number ?? '---' }}</strong><br>
                        <small class="text-muted">{{ $record->voucher_number }}</small>
                    </td>
                    <td>{{ $record->payee->name }}</td>
                    <td><span class="badge bg-secondary">{{ $record->fundSource->code }}</span></td>
                    <td class="text-end fw-bold">{{ number_format($record->net_amount, 2) }}</td>
                    <td class="text-center">
                        @if($record->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('disbursements.show', $record->id) }}" class="btn btn-sm btn-info text-white">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $disbursements->links() }} </div>
@endsection