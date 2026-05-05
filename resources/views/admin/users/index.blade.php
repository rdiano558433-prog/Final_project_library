@extends('layouts.app')
@section('title', 'Manage Users')
@section('page-title', 'User Management')

@section('content')
<div class="py-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <form method="GET" class="flex gap-2 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search name, email, ID..."
                class="border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <select name="role" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                <option value="staff" {{ request('role')=='staff'?'selected':'' }}>Staff</option>
                <option value="user"  {{ request('role')=='user'?'selected':'' }}>Member</option>
            </select>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
            @if(request()->anyFilled(['search','role']))
                <a href="{{ route('admin.users.index') }}" class="border px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Clear</a>
            @endif
        </form>
        <a href="{{ route('admin.users.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium flex items-center gap-2 whitespace-nowrap">
            <span>+</span> Register User
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">#</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Name</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Email</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Student ID</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Role</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Joined</th>
                    <th class="text-center px-5 py-3 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 text-gray-400">{{ $user->id }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->student_id ?? '—' }}</td>
                    <td class="px-5 py-3">
                        @php
                            $rc = ['admin'=>'bg-red-100 text-red-700','staff'=>'bg-yellow-100 text-yellow-700','user'=>'bg-blue-100 text-blue-700'];
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $rc[$user->role] }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium text-xs">View</a>
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="text-yellow-600 hover:text-yellow-800 font-medium text-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-gray-400">
                        <span class="text-4xl">👥</span>
                        <p class="mt-2">No users found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t bg-gray-50">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection