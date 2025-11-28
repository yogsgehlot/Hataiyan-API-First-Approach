@extends('layouts.app')

@section('content')

<div class="max-w-lg mx-auto bg-white dark:bg-slate-800 rounded-xl p-6 shadow mt-10">

    <h2 class="text-xl font-bold mb-4">Report Post</h2>

    <div class="mb-4 p-4 rounded-lg border dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
        <p class="font-semibold">{{ $post->user->name }}</p>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $post->content }}</p>
    </div>

    @if(session('error'))
        <div class="text-red-600 mb-3 text-sm">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="text-green-600 mb-3 text-sm">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('report.post.store', $post->id) }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Reason</label>
            <select name="reason"
                class="w-full p-2 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700">
                <option value="">Select reason</option>
                <option value="Spam">Spam</option>
                <option value="Harassment">Harassment</option>
                <option value="Fake information">Fake Information</option>
                <option value="Violence">Violence</option>
                <option value="Hate Speech">Hate Speech</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Details (optional)</label>
            <textarea name="details" rows="4"
                class="w-full p-3 rounded-lg border dark:border-slate-600 bg-white dark:bg-slate-700">{{ old('details') }}</textarea>
        </div>

        <button class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
            Submit Report
        </button>

    </form>
</div>

@endsection
