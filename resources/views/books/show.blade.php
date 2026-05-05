@extends('layouts.app')
@section('title', $book->title)
@section('page-title', 'Book Details')

@section('content')
<div class="py-4">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Book Card --}}
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="h-48 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-lg flex items-center justify-center text-6xl mb-5">
                📖
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-1">{{ $book->title }}</h2>
            <p class="text-gray-500 mb-1">by {{ $book->author }}</p>
            @if($book->category)
            <span class="inline-block bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded mb-3">{{ $book->category }}</span>
            @endif

            <div class="space-y-2 text-sm mt-4">
                <div class="flex justify-between"><span class="text-gray-500">ISBN</span><span class="font-mono text-xs">{{ $book->isbn }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Publisher</span><span>{{ $book->publisher ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Year</span><span>{{ $book->published_year ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Total Copies</span><span class="font-semibold">{{ $book->total_copies }}</span></div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Available</span>
                    <span class="font-semibold {{ $book->available_copies > 0 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $book->available_copies }}
                    </span>
                </div>
            </div>

            @if(auth()->user()->isAdminOrStaff())
            @php
                $role = auth()->user()->role;
                $editRoute = $role === 'admin' ? route('admin.books.edit', $book) : route('staff.books.edit', $book);
                $indexRoute = $role === 'admin' ? route('admin.books.index') : route('staff.books.index');
                $deleteRoute = $role === 'admin' ? route('admin.books.destroy', $book) : null;
            @endphp
            <div class="mt-5 flex gap-2 flex-wrap">
                <a href="{{ $editRoute }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-medium">Edit</a>
                @if($deleteRoute && auth()->user()->isAdmin())
                <form method="POST" action="{{ $deleteRoute }}" onsubmit="return confirm('Delete this book?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Delete</button>
                </form>
                @endif
                <a href="{{ $indexRoute }}" class="flex-1 text-center border py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Back</a>
            </div>
            @endif

            
        </div>

        {{-- Description + Borrow History --}}
        <div class="lg:col-span-2 space-y-6">
            @if($book->description)
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-700 mb-2">Description</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $book->description }}</p>

                @if(auth()->check() && auth()->user()->role === 'user')

    <div class="mt-5">

        @if($book->available_copies > 0)

            <form method="POST" action="{{ route('user.books.borrow', $book) }}">
                @csrf

                <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg">
                    📚 Borrow This Book
                </button>
            </form>

        @else
            <button disabled class="w-full bg-gray-300 text-gray-600 py-2 rounded-lg">
                Not Available
            </button>
        @endif

    </div>

@endif
            </div>
            @endif

            @if(auth()->user()->isAdminOrStaff())
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="font-semibold text-gray-700 mb-4">📋 Recent Borrowings</h3>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-4 py-2 font-medium text-gray-600">Member</th>
                            <th class="text-left px-4 py-2 font-medium text-gray-600">Borrowed</th>
                            <th class="text-left px-4 py-2 font-medium text-gray-600">Due</th>
                            <th class="text-left px-4 py-2 font-medium text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($borrowings as $b)
                        <tr>
                            <td class="px-4 py-2">{{ $b->user->name }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $b->borrow_date->format('M d, Y') }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $b->due_date->format('M d, Y') }}</td>
                            <td class="px-4 py-2">
                                @php $sc=['borrowed'=>'bg-blue-100 text-blue-700','returned'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700']; @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sc[$b->status] }}">{{ ucfirst($b->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-400">No borrowing history.</td></tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection