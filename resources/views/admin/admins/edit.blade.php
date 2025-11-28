@extends('admin.layouts.app')

@section('content')

<div class="max-w-xl mx-auto space-y-8">

    <h1 class="text-2xl font-bold">Edit Admin</h1>

    <form method="POST" action="{{ route('admin.admins.update', $admin->id) }}"
          class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-semibold">Name</label>
            <input type="text" name="name" value="{{ $admin->name }}"
                class="w-full p-2 rounded-lg bg-slate-100 dark:bg-slate-700 border dark:border-slate-600">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold">Email</label>
            <input type="email" name="email" value="{{ $admin->email }}"
                class="w-full p-2 rounded-lg bg-slate-100 dark:bg-slate-700 border dark:border-slate-600">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold">Role</label>
            <select name="role"
                class="w-full p-2 rounded-lg bg-slate-100 dark:bg-slate-700 border dark:border-slate-600">
                <option value="admin" {{ $admin->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="super" {{ $admin->role == 'super' ? 'selected' : '' }}>Super Admin</option>
            </select>
        </div>

        <button class="w-full px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition">
            Save Changes
        </button>

    </form>

</div>

@endsection
