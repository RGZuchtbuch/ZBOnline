<script>
    import {router} from 'tinro';
    import api      from '../../../js/api.js';

    import Selector from './Selector.svelte';
    import Header   from './Header.svelte';
    import Breed    from './Breed.svelte';
    import Help     from './Help.svelte';

    export let districtId = null;

    let year = null ;
    let sectionId = null;
    let group = null

    let district = null;
    let breeds = [];

    let saveCount = 0;
    let help = false; // triggered in selector

    function onQuery( route ) {
        console.log( 'Query changed' );
        sectionId = route.query.section ? Number( route.query.section ) : 3; // get from route or 3
        year      = route.query.year   ? Number( route.query.year )    : new Date().getFullYear();
        group     = route.query.group  && ['I', 'II', 'III'].includes( route.query.group ) ? route.query.group : 'I';
        saveCount = 0;
        loadDistrict();
        loadBreeds();
    }

    function onHelp( event ) {
        help = ! help;
        console.log( 'Help', help );
    }

    function loadDistrict() {
        api.district.get( districtId ).then( response => {
            district = response.district
        });
    }

    function loadBreeds() {
        breeds = []; // empty
        if( sectionId === 5 ) group = 'I'; // pigeons don't have group, so defaults to 'I' locally
        if( sectionId && year && group ) { // on change of any reload all
            api.district.results.section.get( districtId, sectionId, year, group ).then( response => {
                breeds = response.results;
            });
        }
    }


    $: onQuery( $router );

</script>

<div class='w-256 flex bg-header rounded-t'>
    <div class='w-8'></div>
    <h2 class='grow bg-header text-white text-center'>Eingabe Leistungen {district ? district.name : '...'}</h2>
    <div class='w-8 justify-center m-2 circled bg-alert cursor-pointer' on:click={onHelp}>?</div>
</div>


<Selector bind:year bind:sectionId bind:group/>

<Header {saveCount} />

<div class='w-256 bg-gray-200 overflow-y-scroll border border-t-gray-400 rounded-b scrollbar'>
    {#each breeds as breed }
        <Breed {districtId} {sectionId} {breed} {year} {group} bind:saveCount/>
    {/each}
</div>
{#if help}
    <Help on:help={onHelp} />
{/if}