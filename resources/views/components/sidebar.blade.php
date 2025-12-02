<!-- Sidebar Menu (Tailwind CSS + Heroicons) -->
<ul class="space-y-1 rounded-lg shadow-sm p-2">

  <!-- Home -->
  <li>
    <a href="/"
      class="flex items-center gap-3 px-4 py-2 rounded-md font-medium transition-colors
      {{ Request::is('/') ? 'bg-blue-600 text-white' : 'text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
        stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z" />
      </svg>
      <span>Home</span>
    </a>
  </li>

  <!-- Explore -->
  <li>
    <a href="{{ route('explore.feed') }}"
      class="flex items-center gap-3 px-4 py-2 rounded-md font-medium transition-colors
      {{ Request::is('explore') ? 'bg-blue-600 text-white' : 'text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
        stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M11.28 3.54a.75.75 0 011.44 0l2.14 6.58a.75.75 0 00.71.52h6.9a.75.75 0 01.44 1.35l-5.58 4.06a.75.75 0 00-.27.83l2.14 6.58a.75.75 0 01-1.15.83L12 19.52l-5.57 4.06a.75.75 0 01-1.16-.83l2.15-6.58a.75.75 0 00-.27-.83L1.56 12a.75.75 0 01.44-1.35h6.9a.75.75 0 00.71-.52l2.14-6.58z" />
      </svg>
      <span>Explore</span>
    </a>
  </li>

  <!-- Notifications -->
  <!-- Notifications -->
  <li>
    <a href="{{route('user.notifications')}}"
      class="flex items-center gap-3 px-4 py-2 rounded-md font-medium transition-colors
       {{ Request::is('notifications') ? 'bg-blue-600 text-white' : 'text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}">

      <!-- Bell Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
        stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 00-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 01-6 0" />
      </svg>

      <span>Notifications</span>

      <!-- Badge -->
      @if(isset($unreadCount) && $unreadCount > 0)
        <span class="ml-auto bg-red-600 text-white text-xs font-bold rounded-full px-2 py-0.5">
          {{ $unreadCount }}
        </span>
      @endif

    </a>
  </li>


  <!-- Messages -->
  <!-- <li>
    <a href="/messages"
      class="flex items-center gap-3 px-4 py-2 rounded-md font-medium transition-colors
      {{ Request::is('messages') ? 'bg-blue-600 text-white' : 'text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
        stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
      </svg>
      <span>Messages</span>
    </a>
  </li> -->

  <!-- Profile -->
  <li class="pt-2 border-t border-gray-200 dark:border-gray-700">
    <a href="{{ route('profile') }}"
      class="flex items-center gap-3 px-4 py-2 rounded-md font-medium transition-colors
      {{ Request::is('profile') ? 'bg-blue-600 text-white' : 'text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
      <img src="{{ session('user.avatar_path') ?? asset('images/default-avatar.jpg') }}" alt="Profile"
        class="w-9 h-9 rounded-full border object-cover">
      <span>{{ session('user.username') }}</span>
    </a>
  </li>
  <!-- Create Post -->
  <li>
    <a href="{{ route('post.create') }}"
      class="flex items-center gap-3 px-4 py-2 rounded-md font-medium transition-colors
      {{ Request::is('posts/create') ? 'bg-blue-600 text-white' : 'text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
        stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
      </svg>
      <span>Create Post</span>
    </a>
  </li>


  <!-- Logout -->
  <li>
    <form action="{{ route('logout') }}" method="POST" class="w-full">
      @csrf
      <button type="submit"
        class="flex items-center gap-3 w-full px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-md font-medium transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
          stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
        </svg>
        <span>Logout</span>
      </button>
    </form>
  </li>

</ul>