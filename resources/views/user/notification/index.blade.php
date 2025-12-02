@extends('layouts.app')

@section('content')

    <div class="max-w-2xl mx-auto space-y-8 py-4">

        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Notifications</h1>

        {{-- If no notifications --}}
        @if(empty($notifications['unread']) && empty($notifications['read']))
            <div class="p-10 text-center text-gray-500 dark:text-gray-400">
                You don't have any notifications yet.
            </div>
        @endif

        {{-- ========================================================= --}}
        {{-- UNREAD NOTIFICATIONS --}}
        {{-- ========================================================= --}}
        @if(!empty($notifications['unread']))
            <div class="space-y-4">

                <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase">
                    Unread ({{ count($notifications['unread']) }})
                </h2>

                @foreach($notifications['unread'] as $n)

                    @php
                        // Build human readable message
                        switch ($n['type']) {

                            case 'follow':
                                $actionText = 'started following you';
                                break;

                            case 'unfollow':
                                $actionText = 'unfollowed you';
                                break;

                            case 'like':
                                $actionText = 'liked your post';
                                break;

                            case 'mention':
                                $actionText = 'mentioned you';
                                break;

                            case 'admin_trashed':
                                $actionText = 'trashed your ' . ($n['data']['target_type'] ?? '');
                                break;

                            case 'admin_restored':
                                $actionText = 'restored your ' . ($n['data']['target_type'] ?? '');
                                break;

                            case 'report_actioned':
                                $actionText = 'reviewed a report (' . ($n['data']['action'] ?? '') . ')';
                                break;

                            default:
                                $actionText = 'sent a notification';
                        }
                    @endphp

                    {{-- REDIRECT HANDLER --}}
                    <a href="{{ route('notifications.redirect', ['notification' => $n['id']]) }}" class="flex items-start gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700
                                          bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800
                                          transition shadow-sm relative">

                        {{-- Unread dot --}}
                        <span class="absolute right-4 top-4 h-3 w-3 bg-blue-600 rounded-full"></span>

                        {{-- Actor Avatar --}}
                        <img src="{{ $n['actor_avatar'] ?? '/images/default-avatar.jpg' }}"
                            class="h-12 w-12 rounded-full object-cover border border-gray-300 dark:border-gray-700">

                        <div class="flex-1">

                            {{-- Actor --}}
                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $n['actor_name'] ?? 'Unknown User' }}
                                <span class="font-normal text-gray-600 dark:text-gray-400">
                                    {{ $actionText }}
                                </span>
                            </div>

                            {{-- Optional excerpt --}}
                            @if(isset($n['data']['excerpt']))
                                <div class="mt-1 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {{ $n['data']['excerpt'] }}
                                </div>
                            @endif

                            {{-- Timestamp --}}
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                {{  \Carbon\Carbon::parse($n['created_at'])->diffForHumans()  }}
                            </div>

                        </div>
                    </a>

                @endforeach

            </div>
        @endif



        {{-- ========================================================= --}}
        {{-- READ NOTIFICATIONS --}}
        {{-- ========================================================= --}}
        @if(!empty($notifications['read']))
            <div class="space-y-4 pt-6">

                <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase">
                    Earlier
                </h2>

                @foreach($notifications['read'] as $n)

                    @php
                        switch ($n['type']) {

                            case 'follow':
                                $actionText = 'started following you';
                                break;

                            case 'unfollow':
                                $actionText = 'unfollowed you';
                                break;

                            case 'like':
                                $actionText = 'liked your post';
                                break;

                            case 'mention':
                                $actionText = 'mentioned you';
                                break;

                            case 'admin_trashed':
                                $actionText = 'trashed your ' . ($n['data']['target_type'] ?? '');
                                break;

                            case 'admin_restored':
                                $actionText = 'restored your ' . ($n['data']['target_type'] ?? '');
                                break;

                            case 'report_actioned':
                                $actionText = 'reviewed a report (' . ($n['data']['action'] ?? '') . ')';
                                break;

                            default:
                                $actionText = 'sent a notification';
                        }
                    @endphp

                    <a href="{{ route('notifications.redirect', ['notification' => $n['id']]) }}" class="flex items-start gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-800
                              bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800
                              transition relative opacity-90">


                        {{-- Actor Avatar --}}
                        <img src="{{ $n['actor_avatar'] ?? '/images/default-avatar.jpg' }}"
                            class="h-12 w-12 rounded-full object-cover border border-gray-300 dark:border-gray-700">

                        <div class="flex-1">

                            {{-- Actor --}}
                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $n['actor_name'] ?? 'Unknown User' }}
                                <span class="font-normal text-gray-600 dark:text-gray-400">
                                    {{ $actionText }}
                                </span>
                            </div>

                            {{-- Optional excerpt --}}
                            @if(isset($n['data']['excerpt']))
                                <div class="mt-1 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {{ $n['data']['excerpt'] }}
                                </div>
                            @endif

                            {{-- Timestamp --}}
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                {{  \Carbon\Carbon::parse($n['created_at'])->diffForHumans()  }}
                            </div>

                        </div>
                    </a>

                @endforeach

            </div>
        @endif

    </div>

@endsection