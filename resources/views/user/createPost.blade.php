@extends('layouts.app')

@section('content')
    <div class="pt-10 flex items-center justify-center  overflow-auto  ">
        <div
            class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 relative transition-colors duration-300">

            <h1 class="text-2xl font-semibold text-center text-gray-800 dark:text-gray-100 mb-6">Create Post</h1>

            <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Image Upload --}}
                <div
                    class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-5 flex flex-col items-center justify-center cursor-pointer hover:border-pink-400 dark:hover:border-pink-500 transition-colors duration-300">
                    <label for="media" class="flex flex-col items-center justify-center cursor-pointer">
                        <svg class="w-14 h-14 text-gray-400 dark:text-gray-400 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        <span class="text-gray-500 dark:text-gray-300 text-sm md:text-base">Click to upload photo or
                            video</span>
                        <input type="file" name="media" id="media" accept="image/*,video/*" class="hidden" >
                    </label>
                </div>

                {{-- Preview --}}
                <div id="preview" class="w-full h-64 bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden hidden mt-2">
                    <img id="imgPreview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                    <video id="videoPreview" controls class="w-full h-full object-cover hidden"></video>
                </div>

                {{-- Caption --}}
                <div>
                    <textarea name="caption" rows="3" placeholder="Write a caption..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 text-gray-700 dark:text-gray-200 dark:bg-gray-800 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-500 transition-colors duration-300 resize-none"></textarea>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-3 sm:space-y-0 mt-4">
                    <!-- Discard Button -->
                    <button type="button" onclick="window.history.back()"
                        class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold py-2 rounded-lg shadow-sm 
                   hover:bg-gray-300 dark:hover:bg-gray-600 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Discard
                    </button>

                    <!-- Share Button -->
                    <button type="submit"
                        class="flex-1 bg-pink-500 text-white font-semibold py-2 rounded-lg shadow-md 
                   hover:bg-gradient-to-r hover:from-pink-500 hover:to-pink-600 dark:hover:from-pink-600 dark:hover:to-pink-700 
                   transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-pink-400">
                        Share
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        const mediaInput = document.getElementById('media');
        const preview = document.getElementById('preview');
        const imgPreview = document.getElementById('imgPreview');
        const videoPreview = document.getElementById('videoPreview');

        mediaInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            preview.classList.remove('hidden');

            if (file.type.startsWith('image/')) {
                imgPreview.src = URL.createObjectURL(file);
                imgPreview.classList.remove('hidden');
                videoPreview.classList.add('hidden');
            } else if (file.type.startsWith('video/')) {
                videoPreview.src = URL.createObjectURL(file);
                videoPreview.classList.remove('hidden');
                imgPreview.classList.add('hidden');
            }
        });
    </script>
@endsection