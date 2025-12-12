@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-white sidebar shadow-sm collapse"
                style="min-height: 100vh;">
                <div class="position-sticky pt-4 px-3">
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-2 mb-3 text-muted text-uppercase fw-bold"
                        style="font-size: 0.75rem; letter-spacing: 1px;">
                        <span>Management</span>
                    </h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item mb-2">
                            <a class="nav-link active d-flex align-items-center gap-2 p-2 rounded bg-light text-primary fw-bold"
                                href="#">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link text-secondary d-flex align-items-center gap-2 p-2 rounded hover-bg-light"
                                href="{{ route('disbursementadmin.create') }}">
                                <i class="bi bi-plus-circle"></i> New Disbursement
                            </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-3 text-muted text-uppercase fw-bold"
                        style="font-size: 0.75rem; letter-spacing: 1px;">
                        <span>Administration</span>
                    </h6>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-1">
                            <a class="nav-link text-secondary d-flex align-items-center gap-2 p-2 rounded hover-bg-light"
                                href="{{ route('disbursementadmin.fundform') }}">
                                <i class="bi bi-bank"></i> Add Fund Source
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link text-secondary d-flex align-items-center gap-2 p-2 rounded hover-bg-light"
                                href="{{ route('disbursementadmin.payeeform') }}">
                                <i class="bi bi-people"></i> Add Payee
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link text-secondary d-flex align-items-center gap-2 p-2 rounded hover-bg-light"
                                href="#">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 bg-light">

                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <div>
                        <h1 class="h2 fw-bold text-dark mb-0">Disbursement Monitoring</h1>
                        <p class="text-muted small">Manage your financial records and transactions.</p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="{{ route('disbursementadmin.create') }}"
                            class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                            <i class="bi bi-plus-lg"></i> New Entry
                        </a>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm border-start border-4 border-primary">
                            <div class="card-body">
                                <small class="text-uppercase text-muted fw-bold">Pending Approval</small>
                                <div class="fs-4 fw-bold text-dark">
                                    {{ $disbursements->where('status', '!=', 'approved')->count() }} Records
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm border-start border-4 border-success">
                            <div class="card-body">
                                <small class="text-uppercase text-muted fw-bold">Total Approved</small>
                                <div class="fs-4 fw-bold text-dark">
                                    {{ $disbursements->where('status', 'approved')->count() }} Records
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-list-ul me-2"></i>Recent Transactions</h6>
                        <div class="input-group input-group-sm" style="max-width: 250px;">
                            <input type="text" class="form-control" placeholder="Search check number...">
                            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-uppercase text-muted small fw-bold">
                                <tr>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="py-3">Reference</th>
                                    <th class="py-3">Payee</th>
                                    <th class="py-3">Fund</th>
                                    <th class="text-end py-3">Net Amount</th>
                                    <th class="text-center py-3">Status</th>
                                    <th class="text-center py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @foreach ($disbursements as $record)
                                    @php
                                        $days = $record->days_elapsed;
                                        $status = $record->status;

                                        // Default styling
                                        $badgeClass = 'bg-light text-secondary border-secondary-subtle';
                                        $icon = 'bi-hourglass';

                                        if ($status === 'approved') {
                                            // If approved, show as a neutral/success static state
                                            $badgeClass = 'bg-success-subtle text-success border-success-subtle';
                                            $icon = 'bi-check-circle';
                                        } else {
                                            // PENDING: Gradient Logic (Green -> Red)
                                            if ($days < 2) {
                                                $badgeClass = 'bg-success text-white'; // 0-1 Days (Good)
                                            } elseif ($days < 4) {
                                                $badgeClass = 'bg-warning text-dark'; // 2-3 Days (Warning)
                                            } elseif ($days < 5) {
                                                $badgeClass = 'bg-orange text-white'; // 4 Days (Urgent)
                                            } else {
                                                $badgeClass = 'bg-danger text-white fw-bold'; // 5+ Days (Critical)
                                                $icon = 'bi-exclamation-triangle-fill';
                                            }
                                        }
                                    @endphp

                                    <tr>
                                        <td class="px-4 text-nowrap text-secondary">
                                            {{ $record->created_at->format('M d, Y') }}
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark">{{ $record->check_number ?? '---' }}</span>
                                                <span
                                                    class="small text-muted font-monospace">{{ $record->voucher_number }}</span>
                                            </div>
                                        </td>
                                        <td class="fw-medium text-dark">{{ $record->payee->name }}</td>
                                        <td>
                                            <span
                                                class="badge bg-light text-secondary border border-secondary-subtle text-dark">
                                                {{ $record->fundSource->code }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            {{ number_format($record->net_amount, 2) }}
                                        </td>

                                        <td class="text-center">
                                            <span class="badge rounded-pill border {{ $badgeClass }} px-3 py-2"
                                                style="min-width: 90px;">
                                                <i class="bi {{ $icon }} me-1"></i>
                                                {{ $days }} Day{{ $days == 1 ? '' : 's' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            @if ($record->status === 'approved')
                                                <span
                                                    class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3">
                                                    Approved
                                                </span>
                                            @else
                                                <span
                                                    class="badge rounded-pill bg-light text-secondary border border-secondary-subtle px-3">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('disbursements.details', $record->id) }}"
                                                class="btn btn-sm btn-outline-primary border-0 bg-primary-subtle text-primary">
                                                <i class="bi bi-eye-fill"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($disbursements->hasPages())
                        <div class="card-footer bg-white py-3">
                            {{ $disbursements->links() }}
                        </div>
                    @endif
                </div>

            </main>
        </div>
    </div>

    <style>
        /* Add this specifically for the sidebar hover effect */
        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
            color: #0d6efd !important;
            /* Bootstrap Primary Blue */
            transition: all 0.2s;
        }

        .nav-link {
            color: #6c757d;
            font-weight: 500;
        }

        .bg-orange {
            background-color: #fd7e14 !important;
            /* Bootstrap's orange variable */
        }
    </style>
@endsection
