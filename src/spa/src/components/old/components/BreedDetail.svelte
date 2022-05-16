<script>
    import { slide } from 'svelte/transition';
    import api from '../../../scripts/api.js';

    export let id;
    export let onColorSelect = ()=>{};

    function onColor( event ) { onColorSelect( id, this.id) }

    let promise = api.getBreed( id );

    console.log( 'run breedDetail' );
</script>

{#await promise}
    loading
{:then breed}

    <fieldset class='bordered w-256'>
        <legend> Beschreibung </legend>
        <div class='flex flew-row'>
            <div class='w-full'>Description bladiebla</div>
            <fieldset class='bordered w-32'>
                <legend> Farbenschläge </legend>
                {#each breed.colors as color}
                    <div on:click={ onColor.bind( color ) }> {color.name} </div> - -
                {/each}
            </fieldset>
        </div>
    </fieldset>

{:catch error}
    oeps
{/await}


<style>

</style>