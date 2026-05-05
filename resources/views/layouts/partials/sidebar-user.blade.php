@php
$links = [
    ['route' => 'user.dashboard',    'icon' => '🏠', 'label' => 'Dashboard'],
    ['route' => 'user.books.index',   'icon' => '🔍', 'label' => 'Browse Books'],
    ['route' => 'user.my-books',      'icon' => '📖', 'label' => 'My Borrowed Books'],
];
@endphp

@foreach($links as $link)
<a href="{{ route($link['route']) }}"
   class="sidebar-link flex items-center gap-3 mx-2 px-3 py-2.5 rounded-lg transition-colors text-blue-100 text-sm mb-1
          {{ request()->routeIs($link['route'] . '*') ? 'active' : '' }}">
    <span class="text-lg flex-shrink-0">{{ $link['icon'] }}</span>
    <span x-show="sidebarOpen">{{ $link['label'] }}</span>
</a>
@endforeach