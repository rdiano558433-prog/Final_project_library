@extends('layouts.app')
@section('title', 'User Activity')
@section('page-title', 'User Activity Report')

@section('content')
<div class="py-4">
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
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm">Generate</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Rank</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Member</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Student ID</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Email</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Books Borrowed</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Currently Overdue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $i => $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-400 font-medium">#{{ $i+1 }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.users.show', $user) }}" class="font-medium text-blue-600 hover:underline">
                            {{ $user->name }}
                        </a>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->student_id ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-blue-700 font-bold text-base">{{ $user->total_borrowed }}</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        @if($user->overdue_count > 0)
                            <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-bold">{{ $user->overdue_count }}</span>
                        @else
                            <span class="text-green-600 font-medium">✓ None</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection