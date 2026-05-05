@extends('layouts.app')
@section('title', 'Staff Dashboard')
@section('page-title', 'Staff Dashboard')

@section('content')
<div class="py-4">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php
        $cards = [
            ['label'=>'Active Borrowings', 'value'=>$stats['active_borrowings'], 'icon'=>'📋', 'bg'=>'bg-blue-50'],
            ['label'=>'Overdue',           'value'=>$stats['overdue_count'],     'icon'=>'⚠️', 'bg'=>'bg-red-50'],
            ['label'=>'Total Books',       'value'=>$stats['total_books'],       'icon'=>'📚', 'bg'=>'bg-purple-50'],
            ['label'=>'Returned Today',    'value'=>$stats['returned_today'],    'icon'=>'📥', 'bg'=>'bg-green-50'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white border rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">{{ $card['icon'] }}</span>
                <span class="text-2xl font-bold text-gray-800">{{ $card['value'] }}</span>
            </div>
            <p class="text-sm text-gray-500 font-medium">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-700">📋 Recent Borrowings</h2>
                <a href="{{ route('staff.borrowings.index') }}" class="text-blue-600 hover:underline text-sm">View All</a>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-2 font-medium">Member</th>
                        <th class="pb-2 font-medium">Book</th>
                        <th class="pb-2 font-medium">Due</th>
                        <th class="pb-2 font-medium">Status</th>
                        <th class="pb-2 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentBorrowings as $b)
                    <tr>
                        <td class="py-2">{{ Str::limit($b->user->name, 15) }}</td>
                        <td class="py-2 text-gray-500">{{ Str::limit($b->book->title, 20) }}</td>
                        <td class="py-2 text-gray-400 text-xs">{{ $b->due_date->format('M d') }}</td>
                        <td class="py-2">
                            @php $sc=['borrowed'=>'bg-blue-100 text-blue-700','returned'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sc[$b->status] ?? '' }}">{{ ucfirst($b->status) }}</span>
                        </td>
                        <td class="py-2">
                            @if($b->status !== 'returned')
                            <form method="POST" action="{{ route('staff.borrowings.return', $b) }}" onsubmit="return confirm('Return book?')">
                                @csrf
                                <button type="submit" class="text-xs text-green-600 hover:underline">Return</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-6 text-center text-gray-400">No records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-5">
            <h2 class="font-semibold text-gray-700 mb-4">⚡ Quick Actions</h2>
            <div class="space-y-2">
                <a href="{{ route('staff.borrowings.create') }}" class="flex items-center gap-3 w-full bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-3 text-sm font-medium">
                    <span>📤</span> Issue a Book
                </a>
                <a href="{{ route('staff.books.create') }}" class="flex items-center gap-3 w-full bg-purple-600 hover:bg-purple-700 text-white rounded-lg px-4 py-3 text-sm font-medium">
                    <span>📚</span> Add Book
                </a>
                <a href="{{ route('staff.reports.overdue') }}" class="flex items-center gap-3 w-full bg-red-600 hover:bg-red-700 text-white rounded-lg px-4 py-3 text-sm font-medium">
                    <span>⚠️</span> View Overdue
                </a>
            </div>
        </div>
    </div>
</div>
@endsection