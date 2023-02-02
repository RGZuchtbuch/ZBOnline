<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { district, user } from '../../js/store.js'
    import Results from '../Results.svelte';
    import Range from '../input/Range.svelte';



    export let districtId = null;
    export let moderator = null;

    let year = new Date().getFullYear();
    let results = null;

    console.log( 'Moderator', moderator );

    function handle( districtId, year ) {
        if( districtId ) {
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
    <h2 class='text-center'>Obmann {#if $user} {$user.name} {/if} → Verband {#if $district} {$district.name} {/if} → Leistungen</h2>
    <Range label='Jahr' bind:value={year} min={2000} max={new Date().getFullYear()} step={1} />
    {#if results} <Results {results} /> {/if}
{:else}
    NOT AUTORIZED
{/if}



<style>

</style>