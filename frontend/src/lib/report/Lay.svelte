<script>
    import { getProduction, toDate } from '../../js/util.js'

    import InputDate from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputRing from '../input/Ring.svelte';
    import InputText from '../input/Text.svelte';
    import Select from '../select/Select.svelte';

    export let lay;
    export let disabled;

    function setDays( start, end ) {
        console.log( "Lay change", lay.start, lay.end );
        const startDate = new Date( start );
        const endDate = new Date( end );
        lay.days = null;
        if( startDate && endDate ) {
            const dif = 1 + Math.floor((endDate - startDate) / 86400000);
            lay.days = dif > 0 ? dif : null;
        }
    }

    function setProduction( days, eggs, dames ) {
        lay.production = null;
        if( days && eggs && dames ) {
            lay.production = Math.round( getProduction(days, eggs, dames) ); //eggs * 274 / days / dames;
        }
    }

    $: setDays( lay.start, lay.end );
    $: setProduction( lay.days, lay.eggs, lay.dames );


</script>

<div class='flex flex-col my-2'>
    <h4 class='bg-gray-300'>Legeleistung {lay.start}</h4>
    {#if lay }
        <div class='flex flex-row gap-x-1'>
            <div class='grow flex flex-row gap-x-1'>
                <InputDate class='w-24' label={'Gesammelt ab'} bind:value={lay.start} {disabled}/>
                <InputDate class='w-24' label={'Gesammelt bis'} bind:value={lay.end} min={toDate( lay.start )} {disabled}/>
                <InputNumber class='w-16' label={'# Eierzahl'} bind:value={lay.eggs} min=0 max={lay.days * lay.dames} {disabled} />
                <InputNumber class='w-16' label={'∅ Gewicht'} bind:value={lay.weight} min=0 max=99 {disabled} />
            </div>
            <div class='flex flex-row gap-x-1'>
                <InputNumber class='w-16' label='Tagen' value={lay.days} readonly/>
                <InputNumber class='w-16' label='# Hennen' value={lay.dames} readonly/>
                <InputText class='w-16' label='Eier / Jahr' value={lay.production} readonly />
            </div>
        </div>
    {/if}
</div>

<style>

</style>