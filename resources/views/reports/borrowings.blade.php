@extends('layouts.app')
@section('title', 'Borrowing Report')
@section('page-title', 'Borrowing Report')

@section('content')
<div class="py-4">
    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border p-5 mb-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">From</label>
                <input type="date" name="from" value="{{ $from }}"
                    class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">To</label>
                <input type="date" name="to" value="{{ $to }}"
                    class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="all" {{ $status=='all'?'selected':'' }}>All</option>
                    <option value="borrowed" {{ $status=='borrowed'?'selected':'' }}>Borrowed</option>
                    <option value="returned" {{ $status=='returned'?'selected':'' }}>Returned</option>
                    <option value="overdue"  {{ $status=='overdue'?'selected':'' }}>Overdue</option>
                </select>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm">Generate</button>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-blue-700">{{ $totalCount }}</p>
            <p class="text-sm text-blue-600">Total Transactions</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-green-700">{{ $returnedCount }}</p>
            <p class="text-sm text-green-600">Returned</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-red-700">{{ $overdueCount }}</p>
            <p class="text-sm text-red-600">Overdue</p>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-yellow-700">₱{{ number_format($totalFines, 2) }}</p>
            <p class="text-sm text-yellow-600">Total Fines</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="px-5 py-3 border-b flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Showing <strong>{{ $borrowings->count() }}</strong> records
                from <strong>{{ \Carbon\Carbon::parse($from)->format('M d, Y') }}</strong>
                to <strong>{{ \Carbon\Carbon::parse($to)->format('M d, Y') }}</strong>
            </p>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">#</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Member</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Book</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Borrowed</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Due</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Returned</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Status</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Fine</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($borrowings as $b)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-400">{{ $b->id }}</td>
                    <td class="px-5 py-3 font-medium">{{ $b->user->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ Str::limit($b->book->title, 25) }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $b->borrow_date->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $b->due_date->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $b->return_date?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-5 py-3">
                        @php $sc=['borrowed'=>'bg-blue-100 text-blue-700','returned'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700']; @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc[$b->status] }}">{{ ucfirst($b->status) }}</span>
                    </td>
                    <td class="px-5 py-3">{{ $b->fine_amount > 0 ? '₱'.number_format($b->fine_amount,2) : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-12 text-center text-gray-400">No records for this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection