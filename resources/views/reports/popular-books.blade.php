@extends('layouts.app')
@section('title', 'Popular Books')
@section('page-title', 'Most Popular Books')

@section('content')
<div class="py-4">
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Rank</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Title</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Author</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Category</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Total Borrows</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Available Now</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($books as $i => $book)
                <tr class="hover:bg-gray-50 {{ $i < 3 ? 'bg-yellow-50' : '' }}">
                    <td class="px-5 py-3 font-bold text-lg {{ $i===0?'text-yellow-500':($i===1?'text-gray-400':($i===2?'text-orange-400':'text-gray-400')) }}">
                        {{ $i===0?'🥇':($i===1?'🥈':($i===2?'🥉':'#'.($i+1))) }}
                    </td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $book->title }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $book->author }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $book->category ?? '—' }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-blue-700 font-bold text-base">{{ $book->borrowings_count }}</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="{{ $book->available_copies > 0 ? 'text-green-600' : 'text-red-500' }} font-medium">
                            {{ $book->available_copies }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-400">No data available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection