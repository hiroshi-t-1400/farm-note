{{-- src/resources/views/dashboard.blade.php --}}

<x-layouts.layout title="ダッシュボード - 農作業日誌">
    <x-slot:header>
        ダッシュボード
    </x-slot:header>


    <x-dashboard
        :models="$models"
    />


</x-layouts.layout>
