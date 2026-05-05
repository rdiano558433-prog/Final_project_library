<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — LibraMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="text-6xl mb-3">📚</div>
            <h1 class="text-3xl font-extrabold text-white">LibraMS</h1>
            <p class="text-blue-300 text-sm mt-1">Library Management System</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">Sign in to your account</h2>

            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-red-400 @enderror"
                        placeholder="your@email.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-colors text-sm mt-2">
                    Sign In →
                </button>
            </form>

            <div class="mt-6 p-4 bg-gray-50 rounded-xl text-xs text-gray-500 space-y-1">
                <p class="font-semibold text-gray-600 mb-2">Demo Accounts (password: <code class="bg-gray-200 px-1 rounded">password</code>)</p>
                <p>🔐 <strong>Admin:</strong> admin@library.com</p>
                <p>🧑‍💼 <strong>Staff:</strong> staff@library.com</p>
                <p>🧑 <strong>User:</strong> user@library.com</p>
                
            </div>
        </div>

        <p class="text-center text-blue-400 text-xs mt-6">© {{ date('Y') }} LibraMS</p>
    </div>
</body>
</html>