{{-- /var/www/src/resources/views/notifications/index.blade.php --}}

<x-layouts.layout title="通知 - 農作業日誌">
    <x-slot:header>
        通知一覧
    </x-slot>

    <div x-data="notificationsIndex({
        'initialModel': @js($notifications)
    })">

        <template x-if="!hasUnreadNotifications">
            <div>
                <h2 class="text-base font-bold text-gray-900">
                    未読の通知はありません。
                </h2>
            </div>
        </template>

        <template x-if="hasUnreadNotifications">
            <div class="flex flex-col ">

                <template x-for="data in notificationDatas" :key="data.id">
                    <div class="w-fit">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-4 sm:gap-y-2 items-center sm:py-3 border-b border-gray-100 relative hover:bg-blue-50/40 transition-colors group">
                            <a
                                :href="data.url"
                                class="absolute inset-0 z-10"
                            ></a>
                            <span
                                x-text="data.message"
                                class="min-w-0 truncate font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                            </span>
                            <span
                                x-text="data.target_user_name"
                                class="min-w-0 truncate font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                                トマト 太郎
                            </span>
                            <span
                                x-text="data.displayDate"
                                class="min-w-0 truncate text-gray-800">
                                2026-10-01
                            </span>

                        </div>
                    </div>
                </template>
            </div>
        </template>

        {{-- <x-ui.pagenation /> --}}


    </div>


</x-layouts.layout>
