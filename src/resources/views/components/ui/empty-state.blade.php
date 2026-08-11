@props([
    'alertMessage' => '',
    'alertGuide' => '',
    'alertButton' => ''
    ])

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
