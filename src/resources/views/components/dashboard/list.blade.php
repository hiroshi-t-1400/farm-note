@props([
    'title' => '',
    'info' => '',
    ])

        <div class="grid grid-cols-[auto_1fr] gap-x-10 p-1">
            {{-- 各行のタイトル --}}
            <div>
                {{ $title }}
            </div>
            <div class="flex flex-wrap items-start content-start justify-end gap-x-1">
                {{ $info }}
            </div>
        </div>

