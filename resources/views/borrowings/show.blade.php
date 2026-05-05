@extends('layouts.app')
@section('title', 'Borrowing #' . $borrowing->id)
@section('page-title', 'Borrowing Details #' . $borrowing->id)

@section('content')
<div class="py-4 max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-gray-600 text-sm uppercase tracking-wide mb-3">📖 Book Info</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Title</span>
                        <span class="font-medium">{{ $borrowing->book->title }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Author</span>
                        <span>{{ $borrowing->book->author }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">ISBN</span>
                        <span class="font-mono text-xs">{{ $borrowing->book->isbn }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-gray-600 text-sm uppercase tracking-wide mb-3">👤 Borrower Info</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Name</span>
                        <span class="font-medium">{{ $borrowing->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Student ID</span>
                        <span>{{ $borrowing->user->student_id ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span>{{ $borrowing->user->email }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-gray-600 text-sm uppercase tracking-wide mb-3">📅 Transaction Dates</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Borrow Date</span>
                        <span>{{ $borrowing->borrow_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Due Date</span>
                        <span class="{{ $borrowing->is_overdue ? 'text-red-600 font-semibold' : '' }}">
                            {{ $borrowing->due_date->format('M d, Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Return Date</span>
                        <span>{{ $borrowing->return_date?->format('M d, Y') ?? '—' }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-gray-600 text-sm uppercase tracking-wide mb-3">💰 Fine & Status</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Status</span>
                        @php $sc=['borrowed'=>'bg-blue-100 text-blue-700','returned'=>'bg-green-100 text-green-700','overdue'=>'bg-red-100 text-red-700']; @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc[$borrowing->status] }}">
                            {{ ucfirst($borrowing->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Fine Amount</span>
                        <span class="font-semibold text-red-600">
                            {{ $borrowing->fine_amount > 0 ? '₱'.number_format($borrowing->fine_amount,2) : 'None' }}
                        </span>
                    </div>
                    @if($borrowing->is_overdue)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Days Overdue</span>
                        <span class="text-red-600 font-semibold">{{ $borrowing->days_overdue }} days</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Issued By</span>
                        <span>{{ $borrowing->issuedBy->name }}</span>
                    </div>
                    @if($borrowing->returnedTo)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Received By</span>
                        <span>{{ $borrowing->returnedTo->name }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($borrowing->notes)
        <div class="mt-4 p-4 bg-gray-50 rounded-lg border text-sm text-gray-600">
            <span class="font-medium">Notes:</span> {{ $borrowing->notes }}
        </div>
        @endif

        <div class="mt-6 flex gap-3">
            @if($borrowing->status !== 'returned' && auth()->user()->isAdminOrStaff())
            @php $role = auth()->user()->role; @endphp
            <form method="POST"
                  action="{{ $role === 'admin' ? route('admin.borrowings.return', $borrowing) : route('staff.borrowings.return', $borrowing) }}"
                  onsubmit="return confirm('Mark this book as returned?')">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium">
                    ✅ Mark as Returned
                </button>
            </form>
            @endif
            <a href="javascript:history.back()" class="border px-6 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                Back
            </a>
        </div>
    </div>
</div>
@endsection