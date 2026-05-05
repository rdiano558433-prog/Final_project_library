@extends('layouts.app')
@section('title', 'My Borrowed Books')
@section('page-title', 'My Borrowed Books')

@section('content')
<div class="py-4">
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Book</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Author</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Borrowed</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Due Date</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Returned</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Status</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Fine</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($borrowings as $b)
                <tr class="hover:bg-gray-50 {{ $b->status === 'overdue' ? 'bg-red-50' : '' }}">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ Str::limit($b->book->title, 35) }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $b->book->author }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $b->borrow_date->format('M d, Y') }}</td>
                    <td class="px-5 py-3 {{ $b->is_overdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                        {{ $b->due_date->format('M d, Y') }}
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $b->return_date?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-5 py-3">
                        @php $sc=['borrowed'=>'bg-blue-100 text-blue-700','returned'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700']; @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc[$b->status] }}">{{ ucfirst($b->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-red-600 font-medium">
                        {{ $b->fine_amount > 0 ? '₱'.number_format($b->fine_amount,2) : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center text-gray-400">
                        <span class="text-5xl">📭</span>
                        <p class="mt-3">No borrowing records.</p>
                        <a href="{{ route('user.books.index') }}" class="mt-2 inline-block text-blue-600 hover:underline text-sm">Browse books →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t bg-gray-50">{{ $borrowings->links() }}</div>
    </div>
</div>
@endsection