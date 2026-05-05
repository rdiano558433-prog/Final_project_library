@extends('layouts.app')
@section('title', 'Borrowings')
@section('page-title', 'Borrowing Records')

@section('content')
<div class="py-4">

    <div class="flex flex-col sm:flex-row gap-3 mb-5 items-start sm:items-center justify-between">
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search member or book..."
                class="border rounded-lg px-4 py-2 text-sm">

            <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">All Status</option>
                <option value="borrowed" {{ request('status')=='borrowed'?'selected':'' }}>Borrowed</option>
                <option value="returned" {{ request('status')=='returned'?'selected':'' }}>Returned</option>
                <option value="overdue" {{ request('status')=='overdue'?'selected':'' }}>Overdue</option>
            </select>

            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Filter</button>

            @if(request()->anyFilled(['search','status']))
                <a href="{{ url()->current() }}" class="border px-4 py-2 rounded-lg text-sm">Clear</a>
            @endif
        </form>

        @if(auth()->user()->isAdminOrStaff())
            @php $role = auth()->user()->role; @endphp
            <a href="{{ $role === 'admin' ? route('admin.borrowings.create') : route('staff.borrowings.create') }}"
               class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm">
                + Issue Book
            </a>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-5 py-3">#</th>
                    <th class="px-5 py-3">Member</th>
                    <th class="px-5 py-3">Book</th>
                    <th class="px-5 py-3">Borrow</th>
                    <th class="px-5 py-3">Due</th>
                    <th class="px-5 py-3">Return</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Fine</th>
                    <th class="px-5 py-3">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($borrowings as $b)
                <tr>

                    <td class="px-5 py-3 text-gray-400">{{ $b->id }}</td>

                    <td class="px-5 py-3 font-medium">
                        {{ $b->user?->name ?? 'Unknown User' }}
                    </td>

                    {{-- ✅ FIXED BOOK NULL ERROR --}}
                    <td class="px-5 py-3 text-gray-600">
    {{ Str::limit($b->book?->title ?? 'Deleted Book', 25) }}
</td>

                    <td class="px-5 py-3 text-gray-500">
                        {{ $b->borrow_date?->format('M d, Y') ?? '—' }}
                    </td>

                    <td class="px-5 py-3 text-gray-500">
                        {{ $b->due_date?->format('M d, Y') ?? '—' }}
                    </td>

                    <td class="px-5 py-3 text-gray-500">
                        {{ $b->return_date?->format('M d, Y') ?? '—' }}
                    </td>

                    <td class="px-5 py-3">
                        @php
                            $sc = [
                                'borrowed'=>'bg-blue-100 text-blue-700',
                                'returned'=>'bg-green-100 text-green-700',
                                'overdue'=>'bg-red-100 text-red-700'
                            ];
                        @endphp

                        <span class="px-2 py-1 rounded text-xs {{ $sc[$b->status] ?? '' }}">
                            {{ ucfirst($b->status) }}
                        </span>
                    </td>

                    <td class="px-5 py-3">
                        {{ $b->fine_amount > 0 ? '₱'.$b->fine_amount : '—' }}
                    </td>

                    <td class="px-5 py-3">
                        @php $role = auth()->user()->role; @endphp

                        <div class="flex gap-2">

                            <a href="{{ $role === 'admin'
                                ? route('admin.borrowings.show', $b)
                                : route('staff.borrowings.show', $b) }}"
                               class="text-blue-600 text-xs">
                                View
                            </a>

                            @if($b->status !== 'returned')
                            <form method="POST"
                                  action="{{ $role === 'admin'
                                    ? route('admin.borrowings.return', $b)
                                    : route('staff.borrowings.return', $b) }}">
                                @csrf
                                <button class="text-green-600 text-xs">
                                    Return
                                </button>
                            </form>
                            @endif

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-10 text-center text-gray-400">
                        No borrowing records found.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

        <div class="px-5 py-3 border-t">
            {{ $borrowings->withQueryString()->links() }}
        </div>

    </div>
</div>
@endsection