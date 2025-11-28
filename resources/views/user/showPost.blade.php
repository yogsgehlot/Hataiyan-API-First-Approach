@extends('layouts.app')

@section('content')

    <div class="flex justify-center pt-10 pb-20 bg-gray-50 dark:bg-gray-900 min-h-screen">

        <div class="w-full max-w-2xl">

            <livewire:post.card :post="$post"   />

        </div>

    </div>

@endsection