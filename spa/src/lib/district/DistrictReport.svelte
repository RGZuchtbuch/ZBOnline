<script>
    import { onMount } from 'svelte';
    import { meta } from 'tinro';
    import api from '../../js/api.js';

    import Report from '../result/Report.svelte';
    import Range from '../common/Range.svelte';
    import ScrollDiv from '../common/ScrollDiv.svelte';
    import Page from '../common/Page.svelte';

    export let districtId = null;
//    export let moderator = null;

    let district = null;
    let year = new Date().getFullYear();
    let report = null;


    function handle( districtId, year ) {
        if( districtId ) {
            api.district.get( districtId ).then( response => {
                district = response.district;
            })
//            api.district.report.get( districtId, year ).then( response => {
            api.report.get( districtId, year ).then( response => {
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
    <Page>

        <div slot='title'>Verband {#if district} {district.name} {/if} → Leistungen {year}</div>
        <div slot='body'>
            <Range class='w-full px-16' label='Jahr' bind:value={year} min={STARTYEAR} max={new Date().getFullYear()} step={1} title='Schieben um das Jahr zu wählen'/>
            <Report {report} {district} {year}/>
        </div>
    </Page>
{/if}



<style>

</style>