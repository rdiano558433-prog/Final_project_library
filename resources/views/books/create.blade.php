@extends('layouts.app')
@section('title', isset($book) ? 'Edit Book' : 'Add Book')
@section('page-title', isset($book) ? 'Edit Book' : 'Add New Book')

@section('content')
<div class="py-4 max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border p-6">
        @php
            $role = auth()->user()->role;
            $action = isset($book)
                ? ($role === 'admin' ? route('admin.books.update', $book) : route('staff.books.update', $book))
                : ($role === 'admin' ? route('admin.books.store') : route('staff.books.store'));
            $cancelRoute = $role === 'admin' ? route('admin.books.index') : route('staff.books.index');
        @endphp

        <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if(isset($book)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $book->title ?? '') }}" required
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('title') border-red-400 @enderror">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Author <span class="text-red-500">*</span></label>
                    <input type="text" name="author" value="{{ old('author', $book->author ?? '') }}" required
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('author') border-red-400 @enderror">
                    @error('author')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ISBN <span class="text-red-500">*</span></label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}" required
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('isbn') border-red-400 @enderror">
                    @error('isbn')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" value="{{ old('category', $book->category ?? '') }}"
                        placeholder="e.g. Computer Science"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Publisher</label>
                    <input type="text" name="publisher" value="{{ old('publisher', $book->publisher ?? '') }}"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Published Year</label>
                    <input type="number" name="published_year" value="{{ old('published_year', $book->published_year ?? '') }}"
                        min="1000" max="{{ date('Y') }}"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Copies <span class="text-red-500">*</span></label>
                    <input type="number" name="total_copies" value="{{ old('total_copies', $book->total_copies ?? 1) }}"
                        min="1" required
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('total_copies') border-red-400 @enderror">
                    @error('total_copies')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description', $book->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                    <input type="file" name="cover_image" accept="image/*"
                        class="w-full border rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium">
                    {{ isset($book) ? 'Update Book' : 'Add Book' }}
                </button>
                <a href="{{ $cancelRoute }}" class="border px-6 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection