<script>
    import { slide } from 'svelte/transition';
    import api from '../../../scripts/api.js';

    export let id;
    export let onDetailsSelect = ()=>{};
    export let onBreedersSelect = ()=>{};
    export let onResultsSelect = ()=>{};

    function onDetails( event ) { onDetailsSelect( id ) }
    function onBreeders( event ) { onBreedersSelect( id ) }
    function onResults( event ) { onResultsSelect( id ) }

    let promise = api.getBreed( id );

    console.log( 'run breed' );
</script>


{#await promise}
    loading
{:then breed}
    <fieldset class='bordered w-256'>
        <legend> Geflügelrasse {breed.name} </legend>
        <div class='flex flex-col'>
            <div>got breed {breed.name} {breed.colors.length}</div>

            <div class='flex flex-row'>
                <div on:click={ onDetails }>Beschreibung</div> -
                <div on:click={ onBreeders }>Züchter</div> -
                <div on:click={ onResults }>Leistungen</div>
            </div>
        </div>
    </fieldset>


{:catch error}
    oeps
{/await}


<style>

</style>