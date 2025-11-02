@extends('layouts.app')

@section('title', 'Transaction Details - Library TPS')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card detail-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="page-title mb-2">Transaction #{{ $transaction->id }}</h2>
                        <p class="text-muted fs-6 mb-0">{{ $transaction->created_at->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $transaction->status === 'returned' ? 'success' : ($transaction->isOverdue() ? 'danger' : 'warning') }} fs-6">
                            {{ $transaction->isOverdue() && $transaction->status === 'borrowed' ? 'Overdue' : ucfirst($transaction->status) }}
                        </span>
                    </div>
                </div>

                <!-- Book Information -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-book me-2"></i>Book Information</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="fw-bold">{{ $transaction->book->title }}</h6>
                            <p class="text-muted mb-1">by {{ $transaction->book->author }}</p>
                            <p class="text-muted mb-0">ISBN: {{ $transaction->book->isbn }}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="info-badge">{{ $transaction->book->category }}</span>
                        </div>
                    </div>
                </div>

                <!-- Member Information -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-user me-2"></i>Member Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Name:</strong> {{ $transaction->member->name }}</p>
                            <p class="mb-1"><strong>Member ID:</strong> <code>{{ $transaction->member->member_id }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Email:</strong> {{ $transaction->member->email }}</p>
                            <p class="mb-0"><strong>Phone:</strong> {{ $transaction->member->phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Transaction Details -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-calendar me-2"></i>Transaction Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Borrow Date</label>
                                <div class="fw-bold">{{ $transaction->borrow_date->format('F d, Y') }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Due Date</label>
                                <div class="fw-bold text-{{ $transaction->isOverdue() ? 'danger' : 'info' }}">
                                    {{ $transaction->due_date->format('F d, Y') }}
                                    @if($transaction->status === 'borrowed')
                                        <br><small class="text-muted">
                                            {{ $transaction->isOverdue() ? 
                                                abs($transaction->due_date->diffInDays(now())) . ' days overdue' : 
                                                $transaction->due_date->diffInDays(now()) . ' days left'
                                            }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Return Date</label>
                                <div class="fw-bold">
                                    {{ $transaction->return_date ? $transaction->return_date->format('F d, Y') : 'Not returned yet' }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Fine Amount</label>
                                <div class="fw-bold fs-4 text-{{ $transaction->fine_amount > 0 || $transaction->isOverdue() ? 'danger' : 'success' }}">
                                    ₱{{ number_format($transaction->fine_amount > 0 ? $transaction->fine_amount : $transaction->calculateFine(), 2) }}
                                    @if($transaction->isOverdue() && $transaction->status === 'borrowed')
                                        <br><small class="text-muted">₱5.00 per day overdue</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    @if($transaction->status === 'borrowed')
                        <form action="{{ route('transactions.return', $transaction) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success" onclick="return confirm('Mark this book as returned{{ $transaction->calculateFine() > 0 ? ' with fine of ₱' . number_format($transaction->calculateFine(), 2) : '' }}?')">
                                <i class="fas fa-undo me-2"></i>Return Book
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Transactions
                    </a>
                    <a href="{{ route('books.show', $transaction->book) }}" class="btn btn-peach">
                        <i class="fas fa-book me-2"></i>View Book
                    </a>
                    <a href="{{ route('members.show', $transaction->member) }}" class="btn btn-gradient">
                        <i class="fas fa-user me-2"></i>View Member
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Transaction Status -->
        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Transaction Status</h6>
            </div>
            <div class="card-body text-center">
                @if($transaction->status === 'returned')
                    <div class="text-success mb-3">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                    <h5 class="text-success">Returned</h5>
                    <p class="mb-0">Book returned on {{ $transaction->return_date->format('M d, Y') }}</p>
                    @if($transaction->fine_amount > 0)
                        <div class="mt-3 p-2 bg-warning bg-opacity-25 rounded">
                            <small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Fine paid: ₱{{ number_format($transaction->fine_amount, 2) }}</small>
                        </div>
                    @endif
                @elseif($transaction->isOverdue())
                    <div class="text-danger mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x"></i>
                    </div>
                    <h5 class="text-danger">Overdue</h5>
                    <p class="mb-2">{{ abs($transaction->due_date->diffInDays(now())) }} days overdue</p>
                    <div class="alert alert-danger">
                        <strong>Current Fine: ₱{{ number_format($transaction->calculateFine(), 2) }}</strong>
                    </div>
                @else
                    <div class="text-warning mb-3">
                        <i class="fas fa-clock fa-3x"></i>
                    </div>
                    <h5 class="text-warning">Borrowed</h5>
                    <p class="mb-0">{{ $transaction->due_date->diffInDays(now()) }} days remaining</p>
                @endif
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="fas fa-timeline me-2"></i>Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Book Borrowed</h6>
                            <small class="text-muted">{{ $transaction->borrow_date->format('M d, Y g:i A') }}</small>
                        </div>
                    </div>
                    
                    @if($transaction->due_date->isPast() && $transaction->status === 'borrowed')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Due Date Passed</h6>
                            <small class="text-muted">{{ $transaction->due_date->format('M d, Y') }}</small>
                        </div>
                    </div>
                    @endif
                    
                    @if($transaction->return_date)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Book Returned</h6>
                            <small class="text-muted">{{ $transaction->return_date->format('M d, Y g:i A') }}</small>
                        </div>
                    </div>
                    @else
                    <div class="timeline-item">
                        <div class="timeline-marker bg-light"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1 text-muted">Expected Return</h6>
                            <small class="text-muted">{{ $transaction->due_date->format('M d, Y') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        @if($transaction->status === 'borrowed')
        <div class="card mt-4">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="mailto:{{ $transaction->member->email }}?subject=Book Return Reminder&body=Dear {{ $transaction->member->name }},%0D%0A%0D%0AThis is a reminder about your borrowed book:%0D%0A%0D%0ABook: {{ $transaction->book->title }}%0D%0ADue Date: {{ $transaction->due_date->format('F d, Y') }}%0D%0A{{ $transaction->isOverdue() ? 'Current Fine: ₱' . number_format($transaction->calculateFine(), 2) : '' }}%0D%0A%0D%0APlease return the book as soon as possible.%0D%0A%0D%0AThank you." class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-envelope me-2"></i>Send Reminder
                    </a>
                    <a href="tel:{{ $transaction->member->phone }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-phone me-2"></i>Call Member
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--peach-medium);
}

.timeline-item {
    position: relative;
    margin-bottom: 25px;
}

.timeline-marker {
    position: absolute;
    left: -18px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.timeline-content {
    background: rgba(255,255,255,0.8);
    padding: 10px 15px;
    border-radius: 8px;
    border-left: 3px solid var(--peach-medium);
}
</style>
@endsection