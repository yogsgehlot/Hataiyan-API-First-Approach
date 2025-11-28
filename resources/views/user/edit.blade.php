@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6 px-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.862 4.487l1.651 1.651a2.12 2.12 0 010 3.001L9.015 18.637l-3.524.782a.5.5 0 01-.6-.6l.782-3.524 9.498-9.498a2.12 2.12 0 013.001 0z" />
            </svg>
            Edit Profile
        </h2>
    </div>

    <!-- Profile Edit Card -->
    <div class="bg-white dark:bg-gray-900 shadow-lg rounded-xl p-6">

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Cover Image -->
            <div class="relative">
                <img src="{{ $user['cover'] ?: asset('images/background-empty-picture.jpg') }}"
                    alt="Cover Photo"
                    class="w-full h-48 object-cover rounded-xl">

                <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 hover:opacity-100 transition rounded-xl">
                    <label for="cover_image"
                        class="inline-flex items-center gap-2 bg-white/90 hover:bg-white text-gray-800 text-sm font-semibold px-4 py-2 rounded-full cursor-pointer transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Change Cover
                    </label>
                    <input type="file" id="cover_image" name="cover" class="hidden" accept="image/*">
                </div>
            </div>

            <!-- Profile Image -->
            <div class="relative w-32 mx-auto -mt-16">
                <img src="{{ $user['avatar_path'] ?: asset('images/default-avatar.jpg') }}"
                    alt="Profile"
                    class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 object-cover shadow-lg">

                <label for="profile_image"
                    class="absolute bottom-0 right-0 bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 p-2 rounded-full cursor-pointer shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4v16m8-8H4" />
                    </svg>
                </label>
                <input type="file" id="profile_image" name="avatar" class="hidden" accept="image/*">
            </div>

            <!-- Editable Fields -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Name</label>
                    <input type="text" name="name"
                        value="{{ old('name', $user['name']) }}"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Username</label>
                    <input type="text" name="username"
                        value="{{ old('username', $user['username']) }}"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Bio</label>
                    <textarea name="bio" rows="3"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none px-3 py-2 text-sm">{{ old('bio', $user['bio']) }}</textarea>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4v16m8-8H4" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
