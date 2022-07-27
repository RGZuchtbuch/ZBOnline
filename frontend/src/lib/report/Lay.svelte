<script>
    import { getValidDate } from '../../js/util.js'

    import InputDate from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputRing from '../input/Ring.svelte';
    import InputText from '../input/Text.svelte';
    import Select from '../select/Select.svelte';

    export let report;
    export let disabled;

    let dames = null;
    let days = null
    let production = null;

    $: setDames( report.parents );
    $: setDays( report.lay.start, report.lay.end );
    $: setProduction( dames, days, report.lay.eggs );

    function setDames( parents ) {
        console.log( 'Counting dames' );
        let count = 0;
        for( let parent of parents ) {
            if( parent.sex === '0.1' ) count++;
        }
        dames = count;
    }

    function setDays( start, end ) {
        console.log( 'set days', start, end );
        days = null;
        if( start !== null && end !== null ) {
            const from = getValidDate( start, settings.date.min, settings.date.max );
            const to = getValidDate( end, settings.date.min, settings.date.max );
            console.log('set days', start, end, from, to);
            if (!isNaN(from.getFullYear()) && !isNaN(to.getFullYear())) {
                days = 1 + Math.floor((to - from) / 86400000);
                console.log('Days ok', days)
            }
        }
    }
    function setProduction( dames, days, eggs ) {
        production = eggs * 274 / days / dames;
    }



</script>

<div class='flex flex-col my-2'>
    <div>Legeleistung</div>

    <div class='flex flex-row gap-x-1'>
        <InputNumber class='w-16' label='# Hennen' value={dames} readonly/>
        <InputDate class='w-24' label={'Gesammelt ab'} bind:value={report.lay.start} {disabled} />
        <InputDate class='w-24' label={'Gesammelt bis'} bind:value={report.lay.end} {disabled} />
        <InputNumber class='w-16' label='Tagen' value={days} readonly/>
        <InputNumber class='w-16' label={'Eierzahl'} bind:value={report.lay.eggs} min=0 max={(report.parents.length-1)*365} {disabled} />
        <InputNumber class='w-16' label='Eier / Jahr' value={production} readonly />
    </div>

</div>

<style>

</style>