{{-- /var/www/src/resources/views/components/ui/hamburger-badge.blade.php --}}

@if ($hasTask)
    <span
        class="absolute right-0 top-0 block h-2.5 w-2.5 rounded-full bg-red-600"
        aria-label="処理待ちのタスクがあります"
    ></span>
@endif
