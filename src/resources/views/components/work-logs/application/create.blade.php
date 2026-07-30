<x-layouts.app>

<x-slot:content>

    <div class="main-container grid grid-cols-[minmax(min-content,_800px)] gap-4 px-2 place-content-center bg-green-50 min-h-screen ">

        {{ $title }}

        {{ $formHead }}

        {{ $draftUi }}


        <div class="input-form-inner ">

            <x-work-logs.basic-form-section />

            {{ $materials }}

        </div>

        {{ $bottom }}

        </form>


    </div>
</x-slot>


</x-layouts.app>



