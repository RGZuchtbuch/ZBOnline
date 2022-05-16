<script>
    import { slide } from 'svelte/transition';
    import api from '../../../scripts/api.js';

    export let promise;
    export let onBreedSelect;

    function onClick( event ) { onBreedSelect( this.id ) }

    console.log( 'run breeds' )
</script>

{#await promise}
    loading
{:then breeds}
    <ul>
        {#each breeds as breed}
            <li on:click={onClick.bind( breed )}>{breed.name}</li>
        {/each}
    </ul>
{:catch error}
    oeps
{/await}

<style>

</style>