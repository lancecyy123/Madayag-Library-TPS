@extends('layouts.app')

@section('title', 'Dashboard - Library TPS')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="page-title"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
        <p class="text-muted">Welcome to Library Transaction Processing System</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="card stat-card books">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Total Books</h6>
                        <h3 class="mb-0">{{ $stats['total_books'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="card stat-card members">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Active Members</h6>
                        <h3 class="mb-0">{{ $stats['total_members'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="card stat-card borrowed">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Borrowed Books</h6>
                        <h3 class="mb-0">{{ $stats['borrowed_books'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hand-holding fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="card stat-card overdue">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title mb-1">Overdue Books</h6>
                        <h3 class="mb-0">{{ $stats['overdue_books'] }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Transactions -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Transactions</h5>
            </div>
            <div class="card-body">
                @if($recent_transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Book</th>
                                    <th>Member</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->book->title }}</td>
                                    <td>{{ $transaction->member->name }}</td>
                                    <td>{{ $transaction->borrow_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->status === 'returned' ? 'success' : ($transaction->status === 'overdue' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No recent transactions</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('books.create') }}" class="btn btn-gradient">
                        <i class="fas fa-plus me-2"></i>Add New Book
                    </a>
                    <a href="{{ route('members.create') }}" class="btn btn-gradient">
                        <i class="fas fa-user-plus me-2"></i>Register Member
                    </a>
                    <a href="{{ route('transactions.create') }}" class="btn btn-gradient">
                        <i class="fas fa-handshake me-2"></i>Borrow Book
                    </a>
                    <a href="{{ route('transactions.overdue') }}" class="btn btn-outline-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>View Overdue
                    </a>
                </div>
            </div>
        </div>

        @if($overdue_books->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Overdue Books Alert</h6>
            </div>
            <div class="card-body">
                <p class="text-danger mb-2">{{ $overdue_books->count() }} books are overdue!</p>
                @foreach($overdue_books->take(3) as $overdue)
                <div class="small mb-2">
                    <strong>{{ $overdue->book->title }}</strong><br>
                    <span class="text-muted">{{ $overdue->member->name }} - {{ $overdue->due_date->diffForHumans() }}</span>
                </div>
                @endforeach
                @if($overdue_books->count() > 3)
                <a href="{{ route('transactions.overdue') }}" class="btn btn-sm btn-outline-danger">View All</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection