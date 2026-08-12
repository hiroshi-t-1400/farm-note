{{-- src/resources/views/components/work-logs/application/create.blade.php --}}

@props([
    'models' => '',
    ])


<div class="input-form-wrapper">
    <form
        x-data="postForm({
            initialModels: @js($models)
        })"
        @submit.prevent="submitForm"
        action="{{ route('store') }}"
        method="post"
        {{-- レスポンシブに仕様するならコメント解除 --}}
        {{-- @resize.window="windowWidth = window.innerWidth" --}}
    >
        @csrf

        {{ $slot }}


    </form>
</div>
