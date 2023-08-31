<script>
    import { onMount } from 'svelte';
    import BreedSelect from '../common/input/Breed.svelte';

    export let pair;
    export let disabled;
    export let invalid = false;


    function update() {
    }

    function onInput() {
    }

    onMount( () => {
    })

    $: update( pair );

    // note: pair has sectionId, breedId and colorId

</script>


<fieldset class='flex flex-col border rounded border-gray-400' on:input={onInput}>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow' class:invalid> Rasse und Farbenschag</div>
        <div class='w-6'></div>
    </div>

    <div class='flex flex-row p-2 gap-x-1'>
        <BreedSelect class='flex flex-row p-2 gap-x-1' bind:value={pair} bind:invalid={invalid} {disabled}/>
    </div>
</fieldset>


<style>
    .invalid {
        @apply text-red-800;
    }
</style>

