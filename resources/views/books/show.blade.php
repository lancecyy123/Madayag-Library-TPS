@extends('layouts.app')

@section('title', 'Book Details - Library TPS')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card detail-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="page-title mb-2">{{ $book->title }}</h2>
                        <p class="text-muted fs-5 mb-0">by {{ $book->author }}</p>
                    </div>
                    <div class="text-end">
                        <span class="info-badge">{{ $book->category }}</span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">ISBN</label>
                            <div class="fw-bold">{{ $book->isbn }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Category</label>
                            <div><span class="badge-custom">{{ $book->category }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Total Copies</label>
                            <div class="fw-bold fs-4 text-info">{{ $book->total_copies }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Available Copies</label>
                            <div class="fw-bold fs-4 text-{{ $book->available_copies > 0 ? 'success' : 'danger' }}">
                                {{ $book->available_copies }}
                            </div>
                        </div>
                    </div>
                </div>

                @if($book->description)
                <div class="mb-4">
                    <label class="form-label text-muted small">Description</label>
                    <div class="p-3 bg-light rounded-3">{{ $book->description }}</div>
                </div>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('books.edit', $book) }}" class="btn btn-gradient">
                        <i class="fas fa-edit me-2"></i>Edit Book
                    </a>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Books
                    </a>
                    @if($book->available_copies > 0)
                        <a href="{{ route('transactions.create') }}?book_id={{ $book->id }}" class="btn btn-peach">
                            <i class="fas fa-handshake me-2"></i>Borrow This Book
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Availability Status -->
        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Availability Status</h6>
            </div>
            <div class="card-body text-center">
                @if($book->available_copies > 0)
                    <div class="text-success mb-3">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                    <h5 class="text-success">Available</h5>
                    <p class="mb-0">{{ $book->available_copies }} out of {{ $book->total_copies }} copies available</p>
                @else
                    <div class="text-danger mb-3">
                        <i class="fas fa-times-circle fa-3x"></i>
                    </div>
                    <h5 class="text-danger">Not Available</h5>
                    <p class="mb-0">All copies are currently borrowed</p>
                @endif
            </div>
        </div>

        <!-- Transaction History -->
        <div class="card">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Transactions</h6>
            </div>
            <div class="card-body">
                @if($book->transactions->count() > 0)
                    @foreach($book->transactions->take(5) as $transaction)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>{{ $transaction->member->name }}</strong>
                            <br><small class="text-muted">{{ $transaction->borrow_date->format('M d, Y') }}</small>
                        </div>
                        <span class="badge bg-{{ $transaction->status === 'returned' ? 'success' : ($transaction->isOverdue() ? 'danger' : 'warning') }}">
                            {{ $transaction->isOverdue() && $transaction->status === 'borrowed' ? 'Overdue' : ucfirst($transaction->status) }}
                        </span>
                    </div>
                    @if(!$loop->last)<hr>@endif
                    @endforeach
                    
                    @if($book->transactions->count() > 5)
                        <div class="text-center mt-3">
                            <small class="text-muted">And {{ $book->transactions->count() - 5 }} more transactions...</small>
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        No transactions yet
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection