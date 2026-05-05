@extends('layouts.app')
@section('title', 'Issue Book')
@section('page-title', 'Issue a Book')

@section('content')
<div class="py-4 max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border p-6">
        @php $role = auth()->user()->role; @endphp
        <form method="POST"
              action="{{ $role === 'admin' ? route('admin.borrowings.store') : route('staff.borrowings.store') }}"
              class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Member <span class="text-red-500">*</span></label>
                <select name="user_id" required
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('user_id') border-red-400 @enderror">
                    <option value="">— Select Member —</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id')==$user->id?'selected':'' }}>
                        {{ $user->name }} {{ $user->student_id ? "({$user->student_id})" : '' }}
                    </option>
                    @endforeach
                </select>
                @error('user_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Book <span class="text-red-500">*</span></label>
                <select name="book_id" required
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('book_id') border-red-400 @enderror">
                    <option value="">— Select Book —</option>
                    @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ old('book_id')==$book->id?'selected':'' }}>
                        {{ $book->title }} — {{ $book->author }} ({{ $book->available_copies }} available)
                    </option>
                    @endforeach
                </select>
                @error('book_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                <input type="date" name="due_date"
                    value="{{ old('due_date', now()->addDays(14)->toDateString()) }}"
                    min="{{ now()->addDay()->toDateString() }}"
                    required
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('due_date') border-red-400 @enderror">
                @error('due_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2" placeholder="Optional notes..."
                    class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('notes') }}</textarea>
            </div>

            <div class="bg-blue-50 rounded-lg p-4 text-sm text-blue-700">
                ℹ️ Default loan period is <strong>14 days</strong>. Overdue fine: <strong>₱5 per day</strong>.
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium">
                    Issue Book
                </button>
                <a href="{{ $role === 'admin' ? route('admin.borrowings.index') : route('staff.borrowings.index') }}"
                   class="border px-6 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection