@extends('layouts.app')

@section('title', $user->name)
@section('page-title', 'User Details')

@section('content')
<div class="py-6">

    {{-- USER INFO --}}
    <div class="bg-white border rounded-xl shadow-sm p-6">

        <h2 class="text-2xl font-bold text-gray-800">
            {{ $user->name }}
        </h2>

        <p class="text-gray-500">{{ $user->email }}</p>

        <div class="mt-4 space-y-2 text-sm">

            <div class="flex justify-between">
                <span class="text-gray-500">Role</span>
                <span class="font-semibold">{{ $user->role }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">Student ID</span>
                <span>{{ $user->student_id ?? '—' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">Joined</span>
                <span>{{ $user->created_at->format('M d, Y') }}</span>
            </div>

        </div>
    </div>

    {{-- BORROWING HISTORY --}}
    <div class="mt-6 bg-white border rounded-xl shadow-sm p-6">

        <h3 class="font-semibold mb-4">Borrowing History</h3>

        <table class="w-full text-sm">

            <thead class="border-b text-gray-500">
                <tr>
                    <th class="py-2 text-left">Book</th>
                    <th class="py-2 text-left">Borrow Date</th>
                    <th class="py-2 text-left">Due Date</th>
                    <th class="py-2 text-left">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($borrowings as $b)
                <tr class="border-b">

                    <td class="py-2">
                        {{ $b->book?->title ?? 'Deleted Book' }}
                    </td>

                    <td class="py-2 text-gray-500">
                        {{ $b->borrow_date?->format('M d, Y') }}
                    </td>

                    <td class="py-2 text-gray-500">
                        {{ $b->due_date?->format('M d, Y') }}
                    </td>

                    <td class="py-2">
                        <span class="text-xs px-2 py-1 rounded
                        {{ $b->status == 'borrowed' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $b->status == 'returned' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $b->status == 'overdue' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($b->status) }}
                        </span>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-400">
                        No borrowing history
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>
@endsection