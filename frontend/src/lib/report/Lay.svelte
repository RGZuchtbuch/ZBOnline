<script>
    import { getProduction, getValidDate } from '../../js/util.js'

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
    let minEnd = report.lay.start;

    $: setDames( report.parents );
    $: setDays( report.lay.start, report.lay.end );
    $: setProduction( days, report.lay.eggs, dames );

    function setDames( parents ) {
        console.log( 'Counting dames' );
        let count = 0;
        for( let parent of parents ) {
            if( parent.sex === '0.1' ) count++;
        }
        dames = count;
    }

    function setDays( start, end ) {
        const startDate = new Date( start );
        const endDate = new Date( end );
        days = null;
        console.log('set days', start, end);
        if( startDate && endDate ) {
            const dif = 1 + Math.floor((endDate - startDate) / 86400000);
            days = dif > 0 ? dif : null;
            console.log('Days ok', days)
        }
    }

    function setProduction( days, eggs, dames ) {
        production = null;
        if( days && eggs && dames ) {
            production = Math.round( getProduction(days, eggs, dames) ); //eggs * 274 / days / dames;
        }
    }




</script>

<div class='flex flex-col my-2'>
    <div>Legeleistung</div>

    <div class='flex flex-row gap-x-1'>
        <InputDate class='w-24' label={'Gesammelt ab'} bind:value={report.lay.start} {disabled}/>
        <InputDate class='w-24' label={'Gesammelt bis'} bind:value={report.lay.end} min={report.lay.start} {disabled}/>
        <InputNumber class='w-16' label='Tagen' value={days} readonly/>
        <InputNumber class='w-16' label={'Eierzahl'} bind:value={report.lay.eggs} min=0 max={days * dames} {disabled} />
        <InputNumber class='w-16' label='# Hennen' value={dames} readonly/>
        <InputText class='w-16' label='Eier / Jahr' value={production} readonly />
    </div>
</div>

<style>

</style>