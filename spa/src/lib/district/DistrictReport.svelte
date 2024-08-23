<script>
    import { onMount } from 'svelte';
    import { router } from 'tinro';
    import api from '../../js/api.js';

    import Report from './Report.svelte';
    import Range from '../common/Range.svelte';
    import ScrollDiv from '../common/ScrollDiv.svelte';
    import Page from '../common/Page.svelte';

    export let districtId = null;
    export let year = new Date().getFullYear();
//    export let moderator = null;

    let district = null;
    let report = null;


    function handle( districtId, year ) {
        if( districtId ) {
            api.district.get( districtId ).then( response => {
                district = response.district;
            })
//            api.district.report.get( districtId, year ).then( response => {
            api.report.get( districtId, year ).then( response => {
                report = response.report;
            })
        }
    }

    function onChangeYear( event ) {
        router.goto( `/obmann/verband/${districtId}/leistung/${event.target.value}` );
    }

    //const route = meta();

    onMount( () => {
    })

    $: handle( districtId, year );

</script>

{#if district && report}
    <Page>
        <div slot='title' class='flex flex-row justify-between'>
            <div></div>
            <div>Verband {#if district} {district.name} {/if} → Leistungen {year}</div>
            <a class='w-8 border border-black rounded bg-button text-white text-center' href={`/obmann/verband/${districtId}/leistung/${year}/edit`} title='Eingeben'>&#9998;</a>
        </div>

        <div slot='body'>
            <Range class='w-full px-16' label='Jahr' value={year} min={STARTYEAR} max={new Date().getFullYear()} step={1} title='Schieben um das Jahr zu wählen' on:change={onChangeYear}/>
            <Report {report} {district} {year}/>
        </div>
    </Page>
{/if}



<style>

</style>