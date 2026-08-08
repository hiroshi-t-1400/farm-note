{{-- src/resources/views/work-logs/edit.blade.php --}}

<x-layouts.layout title="日誌記録画面 - 農作業日誌">
    <x-slot:header>
        日誌の編集
    </x-slot>

    <x-work-logs.edit-section :models="$models" />

    {{-- </x-work-logs.application.create> --}}

</x-layouts.layout>

