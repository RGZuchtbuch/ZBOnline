<script>
    import { onMount } from 'svelte';
    import { dec, pct } from '../../../js/util.js';

    import api from '../../../js/api.js';
    import {meta, router} from 'tinro';

    import Results from '../../ResultsList.svelte';
    import Select from '../../input/Select.svelte';

    export let districtId;


    let district;

    let results = [];

    const route = meta();
    let year = route.query.year || new Date().getFullYear();

    const years = [];
    for( let i=year; i>=2000; i-- ) {
        years.push(i);
    }

    function handle( districtId ) {
        api.district.results.get( districtId, year ).then( data => {
            district = data.district;
            results = data.results;
            console.log( 'DistrictResults', results );
        })
    }

    function selectYear( year ) {
//        Router.goto( route.match+'?year='+year );
        router.location.query.set( 'year', year );
    }

    onMount( () => {
    });

    $: handle( districtId, year )
    $: selectYear( year );

    console.log( 'Year', year );

</script>


{#if district}
    <h2 class='text-center' >Leistungen für Verband {district.name}</h2>
    <div class='flex justify-center'>
        <Select class='w-24' label='Jahr' bind:value={year}>
            {#each years as year}
                <option value={year} >{year}</option>
            {/each}
        </Select>
    </div>

    <Results results={results} />
{/if}

<style>

</style>