<script>
    import { dec, getProduction, toDate } from '../../js/util.js'
    import validator from '../../js/validator.js';

    import InputDate from '../common/form/input/DateInput.svelte';
    import InputNumber from '../common/form/input/NumberInput.svelte';
    import FormStatus from '../common/form/Status.svelte';


    export let pair;

    const validate = {
        start:      (v) => validator(v).date().between( (pair.year-1)+'-10-01', (pair.year)+'-09-30' ).orNull().isValid(), // 1-10 → 30-09
        end:        (v) => validator(v).date().between( pair.lay.start, (pair.year)+'-12-31' ).orNullIf( ! pair.lay.start ).isValid(), // 1-10 → 30-09
        dames:      (v) => validator(v).number().range( 1, 32 ).orNull().isValid(),
        eggs:       (v) => validator(v).number().range( 0, 2 * pair.lay.days * pair.lay.dames ).orNull().isValid(),
        weight:     (v) => validator(v).number().range( 1.0, 9999.0 ).orNull().isValid(),
    }

    function newLay() {
        return { pairId:pair.id, start:null, end:null, eggs:null, dames:null, weight:null, days:null, result:null };
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
        if ( pair.lay.days && pair.lay.eggs && pair.lay.dames) {
            pair.lay.production = getProduction( pair.lay.days, pair.lay.eggs, pair.lay.dames ); //eggs * 274 / days / dames;
        }
    }

    function update( pair ) { // pair
        if (!pair.lay) {
            pair.lay = newLay();
        }
        setDays();
        setProduction();
    }


    $: update( pair );

</script>

<fieldset class='flex flex-col border rounded border-gray-400' >
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Legeleistung</div>
        <div class='w-6'></div>
    </div>

    <div class='flex justify-end'>
        <FormStatus />
    </div>

    {#if pair.lay }
        <div class='flex flex-row p-2 gap-x-1'>
            <div class='grow flex flex-row gap-x-1'>
                <InputDate class='w-28' label={'Gesammelt ab'} bind:value={pair.lay.start} error='Falsches Datum' validator={validate.start} />
                <InputDate class='w-28' label={'Gesammelt bis'} bind:value={pair.lay.end} error='Falsches Datum' validator={validate.end} />
                <div class='w-2'></div>
                <InputNumber class='w-16' label='# Hennen' bind:value={pair.lay.dames} validator={validate.dames}/>
                <InputNumber class='w-16' label={'# Eierzahl'} bind:value={pair.lay.eggs} error={'0 .. '+pair.lay.days * pair.lay.dames} validator={validate.eggs} />
                <div class='w-4'></div>
                <InputNumber class='w-16' label={'∅ Gewicht'} bind:value={pair.lay.weight} step={0.1} validator={validate.weight} />
            </div>
            <div class='flex flex-row gap-x-1'>
                <InputNumber class='w-16' label='Tagen' value={pair.lay.days} disabled/>
                <InputNumber class='w-16' label='Eier / Jahr' value={dec(pair.lay.production, 1)} disabled />
            </div>
        </div>
    {/if}
</fieldset>

<style>
    .invalid {
        @apply text-red-800;
    }
</style>