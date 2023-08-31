<script>
    import { dec, getProduction, toDate } from '../../js/util.js'

    import InputDate from '../common/input/Date.svelte';
    import InputNumber from '../common/input/Number.svelte';
    import InputText from '../common/input/Text.svelte';

    export let pair;
    export let invalid = false;
    export let disabled;

    function newLay() {
        return { start:null, end:null, eggs:null, dames:null, weight:null, days:null, result:null };
    }


    function setDays() {
        pair.lay.days = null;
        if( pair.lay.start && pair.lay.end ) {
            const startDate = new Date( pair.lay.start );
            const endDate = new Date( pair.lay.end );

            pair.lay.days = null;
            if ( startDate && endDate) {
                const dif = 1 + Math.floor((endDate - startDate) / 86400000);
                pair.lay.days = dif > 0 ? dif : null;
            }
        }
    }

    function setProduction() {
        pair.lay.production = null;
        if ( pair.lay.days && pair.lay.eggs && pair.dames) {
            pair.lay.production = getProduction( pair.lay.days, pair.lay.eggs, pair.dames ); //eggs * 274 / days / dames;
        }
    }

    function update() { // pair
        if (!pair.lay) {
            pair.lay = newLay();
        }
        setDays();
        setProduction();
        validate();
    }

    function validate() { // input
        invalid =
            ( pair.lay.start && ! pair.lay.days ) ||
            ( pair.lay.start && ! pair.lay.eggs ) ||
            pair.lay.eggs < 0 || pair.lay.eggs > (pair.lay.days * pair.dames) ;
    }

    function onInput( event ) {
        //validate();
    }


    $: update( pair );
//    $: validate( input );


</script>

<fieldset class='flex flex-col border rounded border-gray-400' on:input={onInput}>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow' class:invalid>Legeleistung</div>
        <div class='w-6'></div>
    </div>

    {#if pair.lay }
        <div class='flex flex-row p-2 gap-x-1'>
            <div class='grow flex flex-row gap-x-1'>
                <InputDate class='w-24' label={'Gesammelt ab'} bind:value={pair.lay.start} {disabled}/>
                <InputDate class='w-24' label={'Gesammelt bis'} bind:value={pair.lay.end} min={ pair.lay.start } error='Später als Start!' required={pair.lay.start} {disabled}/>
                <InputNumber class='w-16' label={'# Eierzahl'} bind:value={pair.lay.eggs} min=0 max={pair.lay.days * pair.dames} error={'0 .. '+pair.lay.days * pair.dames} required={pair.lay.start} {disabled} />
                <InputNumber class='w-16' label={'∅ Gewicht'} bind:value={pair.lay.weight} min=1 max=999 {disabled} />
            </div>
            <div class='flex flex-row gap-x-1'>
                <InputNumber class='w-16' label='Tagen' value={pair.lay.days} disabled readonly/>
                <InputNumber class='w-16' label='# Hennen' value={pair.dames} disabled readonly/>
                <InputNumber class='w-16' label='Eier / Jahr' value={dec(pair.lay.production)} disabled readonly />
            </div>
        </div>
    {/if}
</fieldset>

<style>
    .invalid {
        @apply text-red-800;
    }
</style>