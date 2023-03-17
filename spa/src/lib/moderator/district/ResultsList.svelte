<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../../js/api.js';
    import { user } from '../../../js/store.js'
    import Results from '../../ResultsList.svelte';
    import Range from '../../input/Range.svelte';

    export let districtId = null;
//    export let moderator = null;

    let district = null;
    let year = new Date().getFullYear();
    let results = null;


    function handle( districtId, year ) {
        if( districtId ) {
            api.district.get( districtId ).then( response => {
                district = response.district;
            })
            api.district.results.get( districtId, year ).then( response => {
                console.log( 'Results', response.results );
                results = response.results;
            })
        }
    }

    const route = meta();

    onMount( () => {
    })

    $: handle( districtId, year );

</script>

{#if $user}
    <h2 class='text-center'>Verband {#if district} {district.name} {/if} → Leistungen {year}</h2>
    <Range class='px-8' label='Jahr' bind:value={year} min={STARTYEAR} max={new Date().getFullYear()} step={1} title='Schieben um das Jahr zu wählen'/>
    <Results class='h256' {results} />
{:else}
    NOT AUTORIZED
{/if}



<style>

</style>