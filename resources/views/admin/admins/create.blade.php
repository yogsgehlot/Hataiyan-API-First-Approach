@extends('admin.layouts.app')

@section('content')

<div class="max-w-xl mx-auto space-y-8">

    <div>
        <a href="{{ route('admin.dashboard') }}"
           class="text-sm text-sky-600 hover:underline dark:text-sky-400">
            ← Back to Dashboard
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow border border-slate-200 dark:border-slate-700">

        <h1 class="text-2xl font-bold mb-6">Create New Admin</h1>

        <form method="POST" action="{{ route('admin.admins.store') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Name</label>
                <input type="text" name="name"
                       class="w-full px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700
                              border border-slate-300 dark:border-slate-600 text-slate-800
                              dark:text-slate-200 focus:ring-2 focus:ring-sky-500 outline-none"
                       value="{{ old('name') }}" required>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Email</label>
                <input type="email" name="email"
                       class="w-full px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700
                              border border-slate-300 dark:border-slate-600 text-slate-800
                              dark:text-slate-200 focus:ring-2 focus:ring-sky-500 outline-none"
                       value="{{ old('email') }}" required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Password</label>
                <input type="password" name="password"
                       class="w-full px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700
                              border border-slate-300 dark:border-slate-600 text-slate-800
                              dark:text-slate-200 focus:ring-2 focus:ring-sky-500 outline-none"
                       required>
            </div>

            <!-- Role -->
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-1">Role</label>
                <select name="role"
                        class="w-full px-3 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 border
                               border-slate-300 dark:border-slate-600 text-slate-800 dark:text-slate-200">
                    <option value="admin">Admin</option>
                    <option value="superadmin">Super Admin</option>
                </select>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full px-4 py-2 bg-sky-600 text-white rounded-lg font-semibold
                           hover:bg-sky-700 transition">
                Create Admin
            </button>

        </form>

    </div>
</div>

@endsection
