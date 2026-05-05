<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function borrowings(Request $request)
    {
        $from   = $request->get('from', Carbon::now()->startOfMonth()->toDateString());
        $to     = $request->get('to', Carbon::now()->toDateString());
        $status = $request->get('status', 'all');

        $query = Borrowing::with(['user', 'book', 'issuedBy'])
            ->whereBetween('borrow_date', [$from, $to]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $borrowings   = $query->latest()->get();
        $totalFines   = $borrowings->sum('fine_amount');
        $totalCount   = $borrowings->count();
        $returnedCount = $borrowings->where('status', 'returned')->count();
        $overdueCount  = $borrowings->where('status', 'overdue')->count();

        return view('reports.borrowings', compact(
            'borrowings', 'from', 'to', 'status',
            'totalFines', 'totalCount', 'returnedCount', 'overdueCount'
        ));
    }

    
    public function inventory(Request $request)
    {
        $category = $request->get('category', 'all');

        $query = Book::withCount(['borrowings as total_borrowed_times']);

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $books      = $query->orderBy('title')->get();
        $categories = Book::distinct()->pluck('category')->filter()->sort()->values();

        $summary = [
            'total_books'       => $books->sum('total_copies'),
            'available_copies'  => $books->sum('available_copies'),
            'borrowed_copies'   => $books->sum('total_copies') - $books->sum('available_copies'),
            'total_titles'      => $books->count(),
        ];

        return view('reports.inventory', compact('books', 'categories', 'category', 'summary'));
    }

    
    public function overdue(Request $request)
    {
        Borrowing::markOverdueRecords();

        $overdues = Borrowing::with(['user', 'book'])
            ->where('status', 'overdue')
            ->orderBy('due_date')
            ->get()
            ->map(function ($b) {
                $b->days_late       = $b->due_date->diffInDays(now());
                $b->computed_fine   = $b->days_late * 5.00;
                return $b;
            });

        $totalFine = $overdues->sum('computed_fine');

        return view('reports.overdue', compact('overdues', 'totalFine'));
    }

    
    public function userActivity(Request $request)
    {
        $from = $request->get('from', Carbon::now()->startOfMonth()->toDateString());
        $to   = $request->get('to', Carbon::now()->toDateString());

        $users = User::where('role', 'user')
            ->withCount(['borrowings as total_borrowed' => function ($q) use ($from, $to) {
                $q->whereBetween('borrow_date', [$from, $to]);
            }])
            ->withCount(['borrowings as overdue_count' => function ($q) {
                $q->where('status', 'overdue');
            }])
            ->orderByDesc('total_borrowed')
            ->get();

        return view('reports.user-activity', compact('users', 'from', 'to'));
    }

    
    public function popularBooks()
    {
        $books = Book::withCount('borrowings')
            ->orderByDesc('borrowings_count')
            ->take(20)
            ->get();

        return view('reports.popular-books', compact('books'));
    }
}