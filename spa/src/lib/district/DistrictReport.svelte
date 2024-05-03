<script>
    import { onMount } from 'svelte';
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'
    import Report from '../result/Report.svelte';
    import Range from '../common/input/Range.svelte';
    import ScrollDiv from '../common/ScrollDiv.svelte';

    export let districtId = null;
//    export let moderator = null;

    let district = null;
    let year = new Date().getFullYear()-1;
    let report = null;


    function handle( districtId, year ) {
        if( districtId ) {
            api.district.get( districtId ).then( response => {
                district = response.district;
            })
            api.district.report.get( districtId, year ).then( response => {
                report = response.report;
                console.log( 'District.report', report );
            })
        }
    }

    const route = meta();

    onMount( () => {
    })

    $: handle( districtId, year );

</script>

{#if district && report}
    <h2 class='w-256 text-center'>Verband {#if district} {district.name} {/if} → Leistungen {year}</h2>
    <Range class='w-228 px-8' label='Jahr' bind:value={year} min={STARTYEAR} max={new Date().getFullYear()} step={1} title='Schieben um das Jahr zu wählen'/>
    <ScrollDiv>
        <Report {report} />
    </ScrollDiv>
{/if}



<style>

</style>