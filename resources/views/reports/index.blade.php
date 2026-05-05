@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="py-4">
    @php $role = auth()->user()->role; @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        <a href="{{ $role==='admin' ? route('admin.reports.borrowings') : route('staff.reports.borrowings') }}"
           class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition-shadow group">
            <div class="text-4xl mb-3">📋</div>
            <h3 class="font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition-colors">Borrowing Report</h3>
            <p class="text-sm text-gray-500">View borrowing transactions filtered by date and status.</p>
        </a>

        <a href="{{ $role==='admin' ? route('admin.reports.inventory') : route('staff.reports.inventory') }}"
           class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition-shadow group">
            <div class="text-4xl mb-3">📚</div>
            <h3 class="font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition-colors">Inventory Report</h3>
            <p class="text-sm text-gray-500">Book inventory — copies available, borrowed, by category.</p>
        </a>

        <a href="{{ $role==='admin' ? route('admin.reports.overdue') : route('staff.reports.overdue') }}"
           class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition-shadow group">
            <div class="text-4xl mb-3">⚠️</div>
            <h3 class="font-bold text-gray-800 mb-1 group-hover:text-red-600 transition-colors">Overdue Report</h3>
            <p class="text-sm text-gray-500">All currently overdue books and computed fines.</p>
        </a>

        @if($role === 'admin')
        <a href="{{ route('admin.reports.user-activity') }}"
           class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition-shadow group">
            <div class="text-4xl mb-3">👥</div>
            <h3 class="font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition-colors">User Activity</h3>
            <p class="text-sm text-gray-500">Member borrowing frequency and overdue counts.</p>
        </a>

        <a href="{{ route('admin.reports.popular') }}"
           class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition-shadow group">
            <div class="text-4xl mb-3">🏆</div>
            <h3 class="font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition-colors">Popular Books</h3>
            <p class="text-sm text-gray-500">Most borrowed books of all time.</p>
        </a>
        @endif
    </div>
</div>
@endsection