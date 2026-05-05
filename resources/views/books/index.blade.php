@extends('layouts.app')

@section('title', 'Books')
@section('page-title', 'Book Catalog')

@section('content')
<div class="py-4">

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5 items-start sm:items-center justify-between">

        <form method="GET" class="flex flex-wrap gap-2">

            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Title, author, ISBN..."
                class="border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-56">

            <select name="category" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>

            <select name="availability" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">All</option>
                <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                Search
            </button>

            @if(request()->anyFilled(['search','category','availability']))
                <a href="{{ url()->current() }}" class="border px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                    Clear
                </a>
            @endif

        </form>

        @if(auth()->user()->isAdminOrStaff())
            <a href="{{ auth()->user()->isAdmin() ? route('admin.books.create') : route('staff.books.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium whitespace-nowrap">
                + Add Book
            </a>
        @endif

    </div>

    {{-- Books Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

        @forelse($books as $book)
        <div class="bg-white rounded-xl shadow-sm border hover:shadow-md transition-shadow overflow-hidden">

            <div class="h-32 bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center text-4xl">
                📖
            </div>

            <div class="p-4">

                <h3 class="font-semibold text-gray-800 text-sm leading-tight mb-1">
                    {{ Str::limit($book->title, 50) }}
                </h3>

                <p class="text-gray-500 text-xs mb-1">
                    {{ $book->author }}
                </p>

                @if($book->category)
                    <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded mb-2">
                        {{ $book->category }}
                    </span>
                @endif

                <div class="flex items-center justify-between mt-2">

                    <span class="text-xs {{ $book->available_copies > 0 ? 'text-green-600' : 'text-red-500' }} font-medium">
                        {{ $book->available_copies }}/{{ $book->total_copies }} avail.
                    </span>

                    @php
                        $role = auth()->user()->role;
                        return redirect()->route($role . '.books.index')
    ->with('success', 'Book deleted successfully.');

                        $showRoute = match ($role) {
                            'admin' => route('admin.books.show', $book),
                            'staff' => route('staff.books.show', $book),
                            default => route('user.books.show', $book),
                        };
                    @endphp

                    <a href="{{ $showRoute }}" class="text-blue-600 hover:underline text-xs font-medium">
                        Details →
                    </a>

                </div>

            </div>
        </div>

        @empty
        <div class="col-span-4 py-16 text-center text-gray-400">
            <span class="text-5xl">📚</span>
            <p class="mt-3">No books found matching your search.</p>
        </div>
        @endforelse

    </div>

    <div class="mt-5">
        {{ $books->withQueryString()->links() }}
    </div>

</div>
@endsection