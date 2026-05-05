<x-guest-layout>

<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow">

        <h2 class="text-2xl font-bold text-center mb-6">Register</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label class="text-sm">Name</label>
                <input type="text" name="name" class="w-full border p-2 rounded" required>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="text-sm">Email</label>
                <input type="email" name="email" class="w-full border p-2 rounded" required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="text-sm">Password</label>
                <input type="password" name="password" class="w-full border p-2 rounded" required>
            </div>

            <!-- Confirm -->
            <div class="mb-4">
                <label class="text-sm">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border p-2 rounded" required>
            </div>

            <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                Create Account
            </button>

        </form>

        <p class="text-sm text-center mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-blue-600">Login</a>
        </p>

    </div>

</div>

</x-guest-layout>