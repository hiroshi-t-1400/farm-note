{{-- /var/www/src/resources/views/components/ui/notification-bell.blade.php --}}

{{-- <a href="{{ route('notifications.index') }}" --}}
<a href="{{ route('dashboard') }}"
    class="relative inline-flex items-center"
    aria-label="通知">
    {{-- https://heroicons.com/ --}}
    <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="w-6 h-6">

        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31
                A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75
                a8.967 8.967 0 0 1-2.312 6.022
                c1.733.64 3.56 1.085 5.455 1.31m5.714 0
                a24.255 24.255 0 0 1-5.714 0m5.714 0
                a3 3 0 1 1-5.714 0" />
    </svg>

    @if (auth()->user()->unreadNotifications()->exists())
        <span
            class="absolute right-0 top-0 block h-2.5 w-2.5
                    rounded-full bg-red-500 ring-2 ring-white"
            aria-label="未読通知があります"
        ></span>
    @endif
</a>
