<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books' => Book::count(),
            'total_members' => Member::where('status', 'active')->count(),
            'borrowed_books' => Transaction::where('status', 'borrowed')->count(),
            'overdue_books' => Transaction::where('status', 'overdue')->count(),
            'available_books' => Book::sum('available_copies'),
        ];

        $recent_transactions = Transaction::with(['book', 'member'])
            ->latest()
            ->take(5)
            ->get();

        $overdue_books = Transaction::with(['book', 'member'])
            ->where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->get();

        return view('dashboard', compact('stats', 'recent_transactions', 'overdue_books'));
    }
}