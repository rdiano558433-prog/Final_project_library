@extends('layouts.app')
@section('title', 'My Dashboard')
@section('page-title', 'Welcome, ' . auth()->user()->name)

@section('content')
<div class="py-4">
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="text-4xl">📖</div>
            <div>
                <p class="text-2xl font-bold text-blue-600">{{ $borrowings->count() }}</p>
                <p class="text-sm text-gray-500">Currently Borrowed</p>
            </div>
        </div>
        <div class="bg-white border rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="text-4xl">⚠️</div>
            <div>
                <p class="text-2xl font-bold text-red-600">{{ $overdues->count() }}</p>
                <p class="text-sm text-gray-500">Overdue Books</p>
            </div>
        </div>
        <div class="bg-white border rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="text-4xl">✅</div>
            <div>
                <p class="text-2xl font-bold text-green-600">{{ auth()->user()->borrowings()->where('status','returned')->count() }}</p>
                <p class="text-sm text-gray-500">Total Returned</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Currently Borrowed --}}
        <div class="bg-white rounded-xl shadow-sm border p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-700">📚 Currently Borrowed</h2>
                <a href="{{ route('user.my-books') }}" class="text-blue-600 hover:underline text-sm">All →</a>
            </div>
            @forelse($borrowings as $b)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border mb-2">
                <div>
                    <p class="font-medium text-sm text-gray-800">{{ Str::limit($b->book->title, 35) }}</p>
                    <p class="text-xs text-gray-500">Due: {{ $b->due_date->format('M d, Y') }}</p>
                </div>
                <span class="text-xs {{ $b->due_date->isPast() ? 'text-red-600 font-semibold' : 'text-green-600' }}">
                    {{ $b->due_date->isPast() ? 'Overdue' : $b->due_date->diffForHumans() }}
                </span>
            </div>
            @empty
            <div class="text-center py-8 text-gray-400">
                <span class="text-3xl">📭</span>
                <p class="mt-2 text-sm">No books currently borrowed.</p>
                <a href="{{ route('user.books.index') }}" class="mt-2 inline-block text-blue-600 hover:underline text-sm">Browse Books →</a>
            </div>
            @endforelse
        </div>

        {{-- Recent History --}}
        <div class="bg-white rounded-xl shadow-sm border p-5">
            <h2 class="font-semibold text-gray-700 mb-4">🕘 Recent History</h2>
            @forelse($history as $b)
            <div class="flex items-center justify-between p-3 rounded-lg border mb-2">
                <div>
                    <p class="font-medium text-sm text-gray-800">{{ Str::limit($b->book->title, 35) }}</p>
                    <p class="text-xs text-gray-500">{{ $b->borrow_date->format('M d, Y') }}</p>
                </div>
                @php $sc=['borrowed'=>'bg-blue-100 text-blue-700','returned'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700']; @endphp
                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $sc[$b->status] }}">{{ ucfirst($b->status) }}</span>
            </div>
            @empty
            <div class="text-center py-8 text-gray-400">
                <p class="text-sm">No history yet.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Overdue Warning --}}
    @if($overdues->count() > 0)
    <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-5">
        <h3 class="font-semibold text-red-700 mb-3">⚠️ You have {{ $overdues->count() }} overdue book(s)</h3>
        @foreach($overdues as $b)
        <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-red-100 mb-2">
            <div>
                <p class="font-medium text-sm">{{ $b->book->title }}</p>
                <p class="text-xs text-red-500">Was due: {{ $b->due_date->format('M d, Y') }}</p>
            </div>
            <p class="text-red-600 font-semibold text-sm">₱{{ $b->calculated_fine }} fine</p>
        </div>
        @endforeach
        <p class="text-xs text-red-500 mt-2">Please return these books immediately. Fine: ₱5/day.</p>
    </div>
    @endif
</div>
@endsection