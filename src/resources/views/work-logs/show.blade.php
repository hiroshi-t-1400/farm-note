<!-- resources/views/work-logs/create.blade.php -->

<x-layouts.layout title="日誌記録画面 - 農作業日誌">
    <x-slot:header>
        日誌の閲覧
    </x-slot>

    <x-work-logs.show-section :workLog="$workLog" />


    {{-- </x-work-logs.application.create> --}}

</x-layouts.layout>

