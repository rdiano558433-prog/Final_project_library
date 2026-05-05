<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        Borrowing::markOverdueRecords();

        $stats = [
            'total_books'       => Book::count(),
            'available_books'   => Book::where('available_copies', '>', 0)->count(),
            'total_users'       => User::where('role', 'user')->count(),
            'total_staff'       => User::where('role', 'staff')->count(),
            'active_borrowings' => Borrowing::where('status', 'borrowed')->count(),
            'overdue_count'     => Borrowing::where('status', 'overdue')->count(),
            'returned_today'    => Borrowing::where('status', 'returned')->whereDate('return_date', today())->count(),
            'borrowed_today'    => Borrowing::whereDate('borrow_date', today())->count(),
        ];

        $recentBorrowings = Borrowing::with(['user', 'book', 'issuedBy'])
            ->latest()
            ->take(10)
            ->get();

        $overdueBorrowings = Borrowing::with(['user', 'book'])
            ->where('status', 'overdue')
            ->take(5)
            ->get();

        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartData[] = [
                'month' => $month->format('M Y'),
                'count' => Borrowing::whereYear('borrow_date', $month->year)
                                    ->whereMonth('borrow_date', $month->month)
                                    ->count(),
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentBorrowings', 'overdueBorrowings', 'chartData'));
    }
}