@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="py-4">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php
        $cards = [
            ['label' => 'Total Books',       'value' => $stats['total_books'],       'icon' => '📚', 'color' => 'blue'],
            ['label' => 'Available Books',   'value' => $stats['available_books'],   'icon' => '✅', 'color' => 'green'],
            ['label' => 'Active Borrowings', 'value' => $stats['active_borrowings'], 'icon' => '📋', 'color' => 'yellow'],
            ['label' => 'Overdue',           'value' => $stats['overdue_count'],     'icon' => '⚠️', 'color' => 'red'],
            ['label' => 'Total Members',     'value' => $stats['total_users'],       'icon' => '👤', 'color' => 'purple'],
            ['label' => 'Total Staff',       'value' => $stats['total_staff'],       'icon' => '🧑‍💼', 'color' => 'indigo'],
            ['label' => 'Borrowed Today',    'value' => $stats['borrowed_today'],    'icon' => '📤', 'color' => 'teal'],
            ['label' => 'Returned Today',    'value' => $stats['returned_today'],    'icon' => '📥', 'color' => 'cyan'],
        ];
        $colors = ['blue'=>'bg-blue-50 border-blue-200 text-blue-700','green'=>'bg-green-50 border-green-200 text-green-700','yellow'=>'bg-yellow-50 border-yellow-200 text-yellow-700','red'=>'bg-red-50 border-red-200 text-red-700','purple'=>'bg-purple-50 border-purple-200 text-purple-700','indigo'=>'bg-indigo-50 border-indigo-200 text-indigo-700','teal'=>'bg-teal-50 border-teal-200 text-teal-700','cyan'=>'bg-cyan-50 border-cyan-200 text-cyan-700'];
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Chart --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border p-5">
            <h2 class="font-semibold text-gray-700 mb-4">📈 Borrowings (Last 6 Months)</h2>
            <canvas id="borrowingChart" height="120"></canvas>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-sm border p-5">
            <h2 class="font-semibold text-gray-700 mb-4">⚡ Quick Actions</h2>
            <div class="space-y-2">
                <a href="{{ route('admin.borrowings.create') }}" class="flex items-center gap-3 w-full bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-3 text-sm font-medium transition-colors">
                    <span>📤</span> Issue a Book
                </a>
                <a href="{{ route('admin.users.create') }}" class="flex items-center gap-3 w-full bg-green-600 hover:bg-green-700 text-white rounded-lg px-4 py-3 text-sm font-medium transition-colors">
                    <span>👤</span> Register User
                </a>
                <a href="{{ route('admin.books.create') }}" class="flex items-center gap-3 w-full bg-purple-600 hover:bg-purple-700 text-white rounded-lg px-4 py-3 text-sm font-medium transition-colors">
                    <span>📚</span> Add Book
                </a>
                <a href="{{ route('admin.reports.overdue') }}" class="flex items-center gap-3 w-full bg-red-600 hover:bg-red-700 text-white rounded-lg px-4 py-3 text-sm font-medium transition-colors">
                    <span>⚠️</span> View Overdue
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Borrowings --}}
        <div class="bg-white rounded-xl shadow-sm border p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-700">📋 Recent Borrowings</h2>
                <a href="{{ route('admin.borrowings.index') }}" class="text-blue-600 hover:underline text-sm">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="pb-2 font-medium">Member</th>
                            <th class="pb-2 font-medium">Book</th>
                            <th class="pb-2 font-medium">Due</th>
                            <th class="pb-2 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentBorrowings as $b)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 font-medium text-gray-800">{{ Str::limit($b->user->name, 15) }}</td>
                            <td class="py-2 text-gray-600">{{ Str::limit($b->book->title, 20) }}</td>
                            <td class="py-2 text-gray-500">{{ $b->due_date->format('M d') }}</td>
                            <td class="py-2">
                                @php
                                    $sc = ['borrowed'=>'bg-blue-100 text-blue-700','returned'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700'];
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sc[$b->status] ?? '' }}">
                                    {{ ucfirst($b->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-400">No borrowings yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Overdue --}}
        <div class="bg-white rounded-xl shadow-sm border p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-700">⚠️ Overdue Books</h2>
                <a href="{{ route('admin.reports.overdue') }}" class="text-red-600 hover:underline text-sm">Full Report</a>
            </div>
            <div class="space-y-3">
                @forelse($overdueBorrowings as $b)
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $b->user->name }}</p>
                        <p class="text-gray-500 text-xs">{{ Str::limit($b->book->title, 30) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-red-600 text-sm font-semibold">{{ $b->due_date->diffForHumans() }}</p>
                        <a href="{{ route('admin.borrowings.show', $b) }}" class="text-blue-600 hover:underline text-xs">Details</a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <span class="text-3xl">🎉</span>
                    <p class="mt-2 text-sm">No overdue books!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('borrowingChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($chartData, 'month')) !!},
        datasets: [{
            label: 'Books Borrowed',
            data: {!! json_encode(array_column($chartData, 'count')) !!},
            backgroundColor: 'rgba(37, 99, 235, 0.7)',
            borderColor: 'rgba(37, 99, 235, 1)',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
@endpush