{{-- src/resources/views/work-logs/index.blade.php --}}

<x-layouts.layout title="日誌記録画面 - 農作業日誌">


        <x-slot:header>
            日誌の一覧
        </x-slot>

        <x-work-logs.index-section :workLog="$workLog" />

</x-layouts.layout>

