@extends('layouts.app')

@section('title', 'Overdue Books - Library TPS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Overdue Books</h2>
        <p class="text-muted">Books that have passed their due date</p>
    </div>
    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>All Transactions
    </a>
</div>

@if($transactions->count() > 0)
    <div class="alert alert-danger mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Alert!</strong> There are {{ $transactions->count() }} overdue books that need immediate attention.
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($transactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Book</th>
                            <th>Member</th>
                            <th>Contact</th>
                            <th>Due Date</th>
                            <th>Days Overdue</th>
                            <th>Fine Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr class="table-danger">
                            <td>
                                <strong>{{ $transaction->book->title }}</strong>
                                <br><small class="text-muted">{{ $transaction->book->author }}</small>
                                <br><small class="text-muted">ISBN: {{ $transaction->book->isbn }}</small>
                            </td>
                            <td>
                                <strong>{{ $transaction->member->name }}</strong>
                                <br><small class="text-muted">{{ $transaction->member->member_id }}</small>
                            </td>
                            <td>
                                <i class="fas fa-envelope me-1"></i>{{ $transaction->member->email }}
                                <br><i class="fas fa-phone me-1"></i>{{ $transaction->member->phone }}
                            </td>
                            <td>
                                <strong class="text-danger">{{ $transaction->due_date->format('M d, Y') }}</strong>
                                <br><small class="text-muted">{{ $transaction->due_date->diffForHumans() }}</small>
                            </td>
                            <td>
                                <span class="badge bg-danger fs-6">
                                    {{ abs($transaction->due_date->diffInDays(now())) }} days
                                </span>
                            </td>
                            <td>
                                <strong class="text-danger fs-5">
                                    ₱{{ number_format($transaction->calculateFine(), 2) }}
                                </strong>
                                <br><small class="text-muted">₱5.00 per day</small>
                            </td>
                            <td>
                                <div class="d-grid gap-1">
                                    <form action="{{ route('transactions.return', $transaction) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this book as returned with fine of ₱{{ number_format($transaction->calculateFine(), 2) }}?')">
                                            <i class="fas fa-undo me-1"></i>Return & Pay Fine
                                        </button>
                                    </form>
                                    <a href="mailto:{{ $transaction->member->email }}?subject=Overdue Book Reminder&body=Dear {{ $transaction->member->name }},%0D%0A%0D%0AThis is a reminder that the book '{{ $transaction->book->title }}' is overdue. Please return it as soon as possible.%0D%0A%0D%0ADue Date: {{ $transaction->due_date->format('F d, Y') }}%0D%0AFine: ₱{{ number_format($transaction->calculateFine(), 2) }}%0D%0A%0D%0AThank you." class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-envelope me-1"></i>Send Reminder
                                    </a>
                                </div>
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
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h4 class="text-success">No Overdue Books!</h4>
                <p class="text-muted">All books are returned on time. Great job!</p>
                <a href="{{ route('transactions.borrowed') }}" class="btn btn-outline-success">
                    <i class="fas fa-hand-holding me-2"></i>View Borrowed Books
                </a>
            </div>
        @endif
    </div>
</div>
@endsection