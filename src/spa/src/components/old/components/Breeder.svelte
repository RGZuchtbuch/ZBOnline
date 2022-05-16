<script>
    import { slide } from 'svelte/transition';
    import api from '../../../scripts/api.js';

    export let id;
    console.log( 'Breeder', id );

    export let onDetailsSelect = ()=>{};
    export let onPairsSelect = ()=>{};
    export let onResultsSelect = ()=>{};

    function onDetails( event ) { onDetailsSelect( id ) }
    function onPairs( event ) { onPairsSelect( id ) }
    function onResults( event ) { onResultsSelect( id ) }

    let promise = api.getBreeder( id );
</script>


{#await promise}
    loading
{:then breeder}
    <fieldset class='bordered w-256'>
        <legend> Züchter </legend>
        <div class='flex flex-col'>
            <div>Züchter {breeder.name}</div>

            <div class='flex flex-row'>
                <div on:click={ onDetails }>Mitglied</div> -
                <div on:click={ onPairs }>Meldungen</div> -
                <div on:click={ onResults }>Leistungen</div>
            </div>
        </div>
    </fieldset>
{:catch error}
    oeps
{/await}


<style>

</style>