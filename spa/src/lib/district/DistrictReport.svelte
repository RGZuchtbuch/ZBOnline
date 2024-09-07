<script>
    import {getContext, onMount} from 'svelte';
    import { router } from 'tinro';
    import api from '../../js/api.js';

    import Report from './Report.svelte';
    import Range from '../common/Range.svelte';
    import ScrollDiv from '../common/ScrollDiv.svelte';
    import Page from '../common/Page.svelte';

    export let year = new Date().getFullYear();

    let district = getContext( 'district' );
    let breeder = getContext( 'breeder' );
    let report = null;

    function update( districtId, year ) {
        breeder.set( null );
        if( districtId && year ) {
            //api.district.get( districtId ).then( response => {
           //     district = response.district;
            //})

            api.report.get( districtId, year ).then( response => {
                report = response.report;
            })
        }
    }

    function onChangeYear( event ) {
        router.goto( `/obmann/verband/${ $district.id }/leistung/${event.target.value}` );
    }

    onMount( () => {
    })

    $: update( $district.id, year );

</script>

{#if $district && report}
    <Page>
        <div slot='title' class='flex flex-row justify-between'>
            <div></div>
            <div>Verband {$district.name} → Leistungen {year}</div>
            <a class='w-8 border-white border rounded bg-button text-white text-center' href={`/obmann/verband/${ $district.id }/leistung/${year}/edit`} title='Eingeben'>&#9998;</a>
        </div>

        <div slot='body'>
            <Range class='w-full px-16' label='Jahr' value={year} min={STARTYEAR} max={new Date().getFullYear()} step={1} title='Schieben um das Jahr zu wählen' on:change={onChangeYear}/>
            <Report {report} {year}/>
        </div>
    </Page>
{/if}



<style>

</style>