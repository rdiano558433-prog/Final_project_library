@extends('layouts.app')
@section('title', 'Inventory Report')
@section('page-title', 'Book Inventory Report')

@section('content')
<div class="py-4">
    <div class="bg-white rounded-xl shadow-sm border p-5 mb-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                <select name="category" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="all">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $category==$cat?'selected':'' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm">Filter</button>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-blue-700">{{ $summary['total_titles'] }}</p>
            <p class="text-sm text-blue-600">Total Titles</p>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-purple-700">{{ $summary['total_books'] }}</p>
            <p class="text-sm text-purple-600">Total Copies</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-green-700">{{ $summary['available_copies'] }}</p>
            <p class="text-sm text-green-600">Available</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-red-700">{{ $summary['borrowed_copies'] }}</p>
            <p class="text-sm text-red-600">Currently Borrowed</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Title</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Author</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Category</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">ISBN</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Total</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Available</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Borrowed</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Times Borrowed</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($books as $book)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ Str::limit($book->title, 35) }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $book->author }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $book->category ?? '—' }}</td>
                    <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $book->isbn }}</td>
                    <td class="px-5 py-3 text-center font-medium">{{ $book->total_copies }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="{{ $book->available_copies > 0 ? 'text-green-600' : 'text-red-500' }} font-semibold">
                            {{ $book->available_copies }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center text-orange-600 font-medium">
                        {{ $book->total_copies - $book->available_copies }}
                    </td>
                    <td class="px-5 py-3 text-center text-blue-600 font-medium">{{ $book->total_borrowed_times }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-12 text-center text-gray-400">No books found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection