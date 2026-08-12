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
    >
        @csrf

        {{ $slot }}


    </form>
</div>
