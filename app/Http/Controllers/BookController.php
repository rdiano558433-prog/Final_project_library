<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->available();
            } else {
                $query->where('available_copies', 0);
            }
        }

        $books = $query->latest()->paginate(12);
        $categories = Book::distinct()->pluck('category')->filter()->sort()->values();

        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'author'         => ['required', 'string', 'max:255'],
            'isbn'           => ['required', 'string', 'unique:books,isbn'],
            'category'       => ['nullable', 'string', 'max:100'],
            'publisher'      => ['nullable', 'string', 'max:255'],
            'published_year' => ['nullable', 'integer', 'min:1000', 'max:' . date('Y')],
            'total_copies'   => ['required', 'integer', 'min:1'],
            'description'    => ['nullable', 'string'],
        ]);

        $validated['available_copies'] = $validated['total_copies'];

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($validated);

          return redirect()->route(auth()->user()->role . '.books.index')
        ->with('success', 'Book added successfully.');
    }

    public function show(Book $book)
    {
        $borrowings = $book->borrowings()->with('user')->latest()->take(10)->get();
        return view('books.show', compact('book', 'borrowings'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'author'         => ['required', 'string', 'max:255'],
            'isbn'           => ['required', 'string', 'unique:books,isbn,' . $book->id],
            'category'       => ['nullable', 'string', 'max:100'],
            'publisher'      => ['nullable', 'string', 'max:255'],
            'published_year' => ['nullable', 'integer', 'min:1000', 'max:' . date('Y')],
            'total_copies'   => ['required', 'integer', 'min:1'],
            'description'    => ['nullable', 'string'],
        ]);

        $diff = $validated['total_copies'] - $book->total_copies;
        $validated['available_copies'] = max(0, $book->available_copies + $diff);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.index')
                         ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        if ($book->activeBorrowings()->count() > 0) {
            return back()->with('error', 'Cannot delete a book that is currently borrowed.');
        }

        $book->delete();
        return redirect()->route('books.index')
                         ->with('success', 'Book deleted successfully.');
    }

    public function search(Request $request)
    {
        $keyword = $request->get('q', '');
        $books = Book::search($keyword)->available()->paginate(10);
        return view('books.search', compact('books', 'keyword'));
    }
}