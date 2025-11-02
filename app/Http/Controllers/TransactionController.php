<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['book', 'member'])
            ->latest()
            ->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $books = Book::where('available_copies', '>', 0)->get();
        $members = Member::where('status', 'active')->get();
        return view('transactions.create', compact('books', 'members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
        ]);

        $book = Book::find($validated['book_id']);
        
        if (!$book->isAvailable()) {
            return redirect()->back()
                ->with('error', 'Book is not available for borrowing!');
        }

        Transaction::create($validated);

        // Update available copies
        $book->decrement('available_copies');

        return redirect()->route('transactions.index')
            ->with('success', 'Book borrowed successfully!');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['book', 'member']);
        return view('transactions.show', compact('transaction'));
    }

    public function returnBook(Transaction $transaction)
    {
        if ($transaction->status === 'returned') {
            return redirect()->back()
                ->with('error', 'Book already returned!');
        }

        $transaction->update([
            'return_date' => now(),
            'status' => 'returned',
            'fine_amount' => $transaction->calculateFine()
        ]);

        // Update available copies
        $transaction->book->increment('available_copies');

        return redirect()->route('transactions.index')
            ->with('success', 'Book returned successfully!');
    }

    public function borrowed()
    {
        $transactions = Transaction::with(['book', 'member'])
            ->where('status', 'borrowed')
            ->paginate(10);
        return view('transactions.borrowed', compact('transactions'));
    }

    public function overdue()
    {
        $transactions = Transaction::with(['book', 'member'])
            ->where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->paginate(10);
        return view('transactions.overdue', compact('transactions'));
    }
}
