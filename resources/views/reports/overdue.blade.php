@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Overdue Reports</h1>

    <h3>Total Fine: {{ $totalFine }}</h3>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>User</th>
                <th>Book</th>
                <th>Due Date</th>
                <th>Fine</th>
            </tr>
        </thead>
        <tbody>
            @foreach($overdues as $item)
                <tr>
                    <td>{{ $item->user->name ?? 'N/A' }}</td>
                    <td>{{ $item->book->title ?? 'N/A' }}</td>
                    <td>{{ $item->due_date }}</td>
                    <td>{{ $item->computed_fine }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection