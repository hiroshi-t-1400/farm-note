{{-- /var/www/src/resources/views/admin/users/approve.blade.php --}}

<x-layouts.layout title="登録承認 - 農作業日誌">
    <x-slot:header>
        申請の承認
    </x-slot>

    <div>

        <div class="py-2 text-base text-gray-700 font-semibold">
            申請の承認を行ってください。
        </div>
    </div>

    <div>
        <x-admin.approvals :changeRequests="$changeRequests">
        </x-admin.approvals>
    </div>

</x-layouts.layout>
