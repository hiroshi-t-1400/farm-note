@props([
    'crop_seasons' => [],
    'header' => '',
    'alertTemplateTag' => `<template x-if=false>`,
    'alertMessage' => 'メッセージはありません。',
    'alertGuide' => '案内はありません。',
    'alertButton' => 'ボタンがありません',
    'TemplateTag' => `<template x-if=false>`,
    'content' => 'debug:no content...',
])

<div>

<div class="flex items-center justify-between mb-4">
    {{ $header }}
</div>

    {{ $alertTemplateTag }}

        <div class="bg-amber-50 border-l-4 border-amber-500 p-6 rounded-r-lg shadow-sm my-4">
            <div class="flex items-start">

                <div class="flex-shrink-0">
                {{-- ！ピクトグラフなどを配置？ --}}
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-amber-800">
                        {{ $alertMessage }}
                    </h3>

                    <p class="mt-1 text-sm text-amber-700">
                        {{ $alertGuide }}
                    </p>

                    <div class="mt-4">
                        {{ $alertButton }}
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{ $TemplateTag }}
        {{ $content }}

    </template>
</div>
