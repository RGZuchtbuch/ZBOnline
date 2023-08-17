<script>
    import { getProduction, toDate } from '../../js/util.js'

    import InputDate from '../common/input/Date.svelte';
    import InputNumber from '../common/input/Number.svelte';
    import InputText from '../common/input/Text.svelte';

    export let pair;
//    export let lay;
    export let disabled;

    function setDays( lay ) {
        if( lay ) {
            console.log("Lay change", lay.start, lay.end);
            lay.days = null;
            if( lay.start && lay.end ) {
                const startDate = new Date(lay.start);
                const endDate = new Date(lay.end);

                lay.days = null;
                if ( startDate && endDate) {
                    const dif = 1 + Math.floor((endDate - startDate) / 86400000);
                    lay.days = dif > 0 ? dif : null;
                }
            }
        }
    }

    function setResult( lay ) {
        if( lay ) {
            lay.production = null;
            if ( lay.days && lay.eggs && lay.dames) {
                lay.production = Math.round(getProduction( lay.days, lay.eggs, lay.dames ) ); //eggs * 274 / days / dames;
            }
        }
    }

    function update( pair ) {
        if( ! pair.lay ) {
            pair.lay = { start:null, end:null, eggs:null, dames:null, weight:null }
        }
        setDays( pair.lay );
        setResult( pair.lay );
    }


    $: update( pair );


</script>

<div class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Legeleistung</div>
        <div class='w-6'></div>
    </div>

    {#if pair.lay }
        <div class='flex flex-row p-2 gap-x-1'>
            <div class='grow flex flex-row gap-x-1'>
                <InputDate class='w-24' label={'Gesammelt ab'} bind:value={pair.lay.start} {disabled}/>
                <InputDate class='w-24' label={'Gesammelt bis'} bind:value={pair.lay.end} min={toDate( pair.lay.start )} {disabled}/>
                <InputNumber class='w-16' label={'# Eierzahl'} bind:value={pair.lay.eggs} min=0 max={pair.lay.days * pair.lay.dames} {disabled} />
                <InputNumber class='w-16' label={'∅ Gewicht'} bind:value={pair.lay.weight} min=0 max=99 {disabled} />
            </div>
            <div class='flex flex-row gap-x-1'>
                <InputNumber class='w-16' label='Tagen' value={pair.lay.days} disabled readonly/>
                <InputNumber class='w-16' label='# Hennen' value={pair.lay.dames} disabled readonly/>
                <InputText class='w-16' label='Eier / Jahr' value={pair.lay.production} disabled readonly />
            </div>
        </div>
    {/if}
</div>

<style>

</style>