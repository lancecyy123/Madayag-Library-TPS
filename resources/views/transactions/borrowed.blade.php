@extends('layouts.app')

@section('title', 'Borrowed Books - Library TPS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><i class="fas fa-hand-holding me-2"></i>Currently Borrowed Books</h2>
        <p class="text-muted">Books that are currently borrowed by members</p>
    </div>
    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>All Transactions
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($transactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Book</th>
                            <th>Member</th>
                            <th>Borrow Date</th>
                            <th>Due Date</th>
                            <th>Days Left</th>
                            <th>Potential Fine</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr class="{{ $transaction->isOverdue() ? 'table-warning' : '' }}">
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
                                @if($transaction->isOverdue())
                                    <span class="badge bg-danger">
                                        {{ abs($transaction->due_date->diffInDays(now())) }} days overdue
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        {{ $transaction->due_date->diffInDays(now()) }} days left
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-{{ $transaction->calculateFine() > 0 ? 'danger' : 'muted' }}">
                                    ₱{{ number_format($transaction->calculateFine(), 2) }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('transactions.return', $transaction) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this book as returned?')">
                                        <i class="fas fa-undo me-1"></i>Return Book
                                    </button>
                                </form>
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
                <i class="fas fa-hand-holding fa-4x text-success mb-3"></i>
                <h4 class="text-muted">No Borrowed Books</h4>
                <p class="text-muted">All books have been returned!</p>
                <a href="{{ route('transactions.create') }}" class="btn btn-gradient">
                    <i class="fas fa-plus me-2"></i>Create New Borrow
                </a>
            </div>
        @endif
    </div>
</div>
@endsection