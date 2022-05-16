<script>
    import { slide } from 'svelte/transition';
    import api from '../../../scripts/api.js';
    import Breeds from './Breeds.svelte';

    export let id;
    export let onBreedSelect;

    let promise = api.getSection( id );

    function onClick( event ) {
        if (onBreedSelect) onBreedSelect(this.id);
    }

    console.log('run section')

</script>


{#await promise}
    loading
{:then section}
    <fieldset class='bordered w-256'>
        <legend> Abteilung {section.name} Rassen </legend>

        <ul>
            {#each section.breeds as breed}
                <li on:click={onClick.bind( breed )}>{breed.name}</li>
            {/each}
        </ul>
    </fieldset>
{:catch error}
    oeps
{/await}


<style>
</style>