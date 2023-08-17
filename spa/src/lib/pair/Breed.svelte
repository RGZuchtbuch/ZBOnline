<script>
    import { onMount } from 'svelte';
    import BreedSelect from '../common/select/Breed.svelte';

    export let pair;
    export let disabled;
    export let invalid = false;


    onMount( () => {
        console.log( 'Breed', pair )
    })

</script>


<div class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'> Rasse und Farbenschag</div>
        <div class='w-6'>{invalid}</div>
    </div>

    <div class='flex flex-row p-2 gap-x-1'>
        <BreedSelect class='flex flex-row p-2 gap-x-1' bind:sectionId={pair.sectionId} bind:breedId={pair.breedId} bind:colorId={pair.colorId} bind:invalid={invalid} {disabled}/>
    </div>
</div>
<style></style>

