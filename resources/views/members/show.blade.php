@extends('layouts.app')

@section('title', 'Member Details - Library TPS')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card detail-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="page-title mb-2">{{ $member->name }}</h2>
                        <p class="text-muted fs-6 mb-0">Member ID: <code>{{ $member->member_id }}</code></p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'secondary' }} fs-6">
                            {{ ucfirst($member->status) }}
                        </span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Email Address</label>
                            <div class="fw-bold">
                                <i class="fas fa-envelope me-2 text-muted"></i>{{ $member->email }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Phone Number</label>
                            <div class="fw-bold">
                                <i class="fas fa-phone me-2 text-muted"></i>{{ $member->phone }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Member Since</label>
                            <div class="fw-bold">
                                <i class="fas fa-calendar me-2 text-muted"></i>{{ $member->membership_date->format('F d, Y') }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Active Borrows</label>
                            <div class="fw-bold fs-4 text-{{ $member->activeBorrows->count() > 0 ? 'warning' : 'success' }}">
                                {{ $member->activeBorrows->count() }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small">Address</label>
                    <div class="p-3 bg-light rounded-3">
                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $member->address }}
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('members.edit', $member) }}" class="btn btn-gradient">
                        <i class="fas fa-edit me-2"></i>Edit Member
                    </a>
                    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Members
                    </a>
                    @if($member->status === 'active')
                        <a href="{{ route('transactions.create') }}?member_id={{ $member->id }}" class="btn btn-peach">
                            <i class="fas fa-plus me-2"></i>New Borrow
                        </a>
                    @endif
                    <a href="mailto:{{ $member->email }}" class="btn btn-outline-info">
                        <i class="fas fa-envelope me-2"></i>Send Email
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Member Statistics -->
        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Member Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-info mb-0">{{ $member->transactions->count() }}</h4>
                            <small class="text-muted">Total Borrows</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-0">{{ $member->transactions->where('status', 'returned')->count() }}</h4>
                        <small class="text-muted">Returned</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-warning mb-0">{{ $member->activeBorrows->count() }}</h4>
                            <small class="text-muted">Active</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-danger mb-0">{{ $member->transactions->filter(function($t) { return $t->isOverdue(); })->count() }}</h4>
                        <small class="text-muted">Overdue</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Borrowed Books -->
        <div class="card">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="fas fa-book me-2"></i>Currently Borrowed</h6>
            </div>
            <div class="card-body">
                @if($member->activeBorrows->count() > 0)
                    @foreach($member->activeBorrows as $transaction)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <strong>{{ $transaction->book->title }}</strong>
                                <br><small class="text-muted">{{ $transaction->book->author }}</small>
                                <br><small class="text-muted">Due: {{ $transaction->due_date->format('M d, Y') }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $transaction->isOverdue() ? 'danger' : 'warning' }}">
                                    {{ $transaction->isOverdue() ? 'Overdue' : 'Borrowed' }}
                                </span>
                                @if($transaction->isOverdue())
                                    <br><small class="text-danger">Fine: ₱{{ number_format($transaction->calculateFine(), 2) }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(!$loop->last)<hr>@endif
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-book-open fa-2x mb-2 d-block"></i>
                        No books currently borrowed
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Transaction History -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Transaction History</h6>
            </div>
            <div class="card-body">
                @if($member->transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Book</th>
                                    <th>Borrow Date</th>
                                    <th>Due Date</th>
                                    <th>Return Date</th>
                                    <th>Status</th>
                                    <th>Fine</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($member->transactions->sortByDesc('created_at') as $transaction)
                                <tr>
                                    <td>
                                        <strong>{{ $transaction->book->title }}</strong>
                                        <br><small class="text-muted">{{ $transaction->book->author }}</small>
                                    </td>
                                    <td>{{ $transaction->borrow_date->format('M d, Y') }}</td>
                                    <td>{{ $transaction->due_date->format('M d, Y') }}</td>
                                    <td>{{ $transaction->return_date ? $transaction->return_date->format('M d, Y') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->status === 'returned' ? 'success' : ($transaction->isOverdue() ? 'danger' : 'warning') }}">
                                            {{ $transaction->isOverdue() && $transaction->status === 'borrowed' ? 'Overdue' : ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-{{ $transaction->fine_amount > 0 || $transaction->isOverdue() ? 'danger' : 'muted' }}">
                                            ₱{{ number_format($transaction->fine_amount > 0 ? $transaction->fine_amount : $transaction->calculateFine(), 2) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        <h5>No transaction history</h5>
                        <p>This member hasn't borrowed any books yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection