<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-900 to-indigo-700 min-h-screen flex items-center justify-center">

<div class="text-center text-white max-w-2xl px-6">

    <h1 class="text-5xl font-bold mb-4">📚 Library Management System</h1>

    <p class="text-lg text-gray-200 mb-8">
        Manage books, borrowings, users, and reports in one smart system.
    </p>

    <div class="space-x-4">
        <a href="{{ route('login') }}"
           class="bg-white text-blue-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200">
            Login
        </a>

        <a href="{{ route('register') }}"
           class="bg-blue-500 px-6 py-3 rounded-lg font-semibold hover:bg-blue-600">
            Register
        </a>
    </div>

</div>

</body>
</html>