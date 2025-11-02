@extends('layouts.app')

@section('title', 'Transactions - Library TPS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><i class="fas fa-exchange-alt me-2"></i>Transaction History</h2>
        <p class="text-muted">View all borrowing transactions</p>
    </div>
    <div>
        <a href="{{ route('transactions.create') }}" class="btn btn-gradient me-2">
            <i class="fas fa-plus me-2"></i>New Transaction
        </a>
        <div class="btn-group">
            <a href="{{ route('transactions.borrowed') }}" class="btn btn-outline-warning">
                <i class="fas fa-hand-holding me-1"></i>Borrowed
            </a>
            <a href="{{ route('transactions.overdue') }}" class="btn btn-outline-danger">
                <i class="fas fa-exclamation-triangle me-1"></i>Overdue
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($transactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Book</th>
                            <th>Member</th>
                            <th>Borrow Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Fine</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td><code>#{{ $transaction->id }}</code></td>
                            <td>
                                <strong>{{ $transaction->book->title }}</strong>
                                <br><small class="text-muted">{{ $transaction->book->author }}</small>
                            </td>
                            <td>
                                <strong>{{ $transaction->member->name }}</strong>
                                <br><small class="text-muted">{{ $transaction->member->member_id }}</small>
                            </td>
                            <td>{{ $transaction->borrow_date->format('M d, Y') }}</td>
                            <td>{{ $transaction->due_date->format('M d, Y') }}</td>
                            <td>
                                {{ $transaction->return_date ? $transaction->return_date->format('M d, Y') : '-' }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $transaction->status === 'returned' ? 'success' : ($transaction->isOverdue() ? 'danger' : 'warning') }}">
                                    {{ $transaction->isOverdue() && $transaction->status === 'borrowed' ? 'Overdue' : ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>
                                @if($transaction->fine_amount > 0 || $transaction->isOverdue())
                                    <span class="text-danger">₱{{ number_format($transaction->fine_amount > 0 ? $transaction->fine_amount : $transaction->calculateFine(), 2) }}</span>
                                @else
                                    <span class="text-muted">₱0.00</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->status === 'borrowed')
                                    <form action="{{ route('transactions.return', $transaction) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this book as returned?')">
                                            <i class="fas fa-undo me-1"></i>Return
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-exchange-alt fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Transactions Found</h4>
                <p class="text-muted">Start by creating your first book borrowing transaction.</p>
                <a href="{{ route('transactions.create') }}" class="btn btn-gradient">
                    <i class="fas fa-plus me-2"></i>Create Transaction
                </a>
            </div>
        @endif
    </div>
</div>
@endsection