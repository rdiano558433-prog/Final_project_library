<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        Borrowing::markOverdueRecords();

        $query = Borrowing::with(['user', 'book', 'issuedBy']);

        $user = Auth::user();

        if ($user && $user->role === 'user') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($q) =>
                    $q->where('name', 'like', "%{$search}%")
                )->orWhereHas('book', fn($q) =>
                    $q->where('title', 'like', "%{$search}%")
                );
            });
        }

        $borrowings = $query->latest()->paginate(15);

        return view('borrowings.index', compact('borrowings'));
    }

    
    public function borrow(Book $book)
{
    $user = auth()->user();

    if ($book->available_copies <= 0) {
        return back()->with('error', 'No copies available.');
    }

    $existing = Borrowing::where('user_id', $user->id)
        ->where('book_id', $book->id)
        ->where('status', 'borrowed')
        ->first();

    if ($existing) {
        return back()->with('error', 'You already borrowed this book.');
    }

    Borrowing::create([
        'user_id'     => $user->id,
        'book_id'     => $book->id,
        'issued_by'   => $user->id,
        'borrow_date' => now(),
        'due_date'    => now()->addDays(7),
        'status'      => 'borrowed',
    ]);

    $book->decrement('available_copies');

    return back()->with('success', 'Book borrowed successfully!');
}

    public function show(Borrowing $borrowing)
    {
        $user = Auth::user();

        if ($user && $user->role === 'user' && $borrowing->user_id !== $user->id) {
            abort(403);
        }

        $borrowing->load(['user', 'book', 'issuedBy', 'returnedTo']);

        return view('borrowings.show', compact('borrowing'));
    }

    public function returnBook(Borrowing $borrowing)
    {
        if ($borrowing->status === 'returned') {
            return back()->with('error', 'Already returned.');
        }

        $fine = $borrowing->calculated_fine ?? 0;

        $borrowing->update([
            'status'      => 'returned',
            'return_date' => Carbon::today(),
            'returned_to' => Auth::id(),
            'fine_amount' => $fine,
        ]);

        $borrowing->book->increment('available_copies');

        return back()->with('success', 'Book returned successfully.');
    }

    public function myBooks()
    {
        $borrowings = Borrowing::with('book')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.my-books', compact('borrowings'));
    }

    
}