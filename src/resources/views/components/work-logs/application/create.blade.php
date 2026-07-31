<x-layouts.app>

<x-slot:content>

    <div class="main-container grid grid-cols-[minmax(min-content,_800px)] gap-4 px-2 place-content-center bg-green-50 min-h-screen ">


        {{ $title }}

        {{ $formHead }}
        

        {{ $header }}

        <x-work-logs.draft-ui />


        <div class="input-form-inner ">

            <x-work-logs.basic-form-section />

            <x-work-logs.material-logs />


        </div>

        {{ $bottom }}

        </form>


    </div>
</x-slot>


</x-layouts.app>



