<script>
    import { getProduction, toDate } from '../../js/util.js'

    import InputDate from '../common/input/Date.svelte';
    import InputNumber from '../common/input/Number.svelte';
    import InputText from '../common/input/Text.svelte';

    export let pair;
    export let invalid = false;
    export let disabled;

    let input;

    function setDays( lay ) {
        if( input && input.lay ) {
            input.lay.days = null;
            if( input.lay.start && input.lay.end ) {
                const startDate = new Date(input.lay.start);
                const endDate = new Date(input.lay.end);

                input.lay.days = null;
                if ( startDate && endDate) {
                    const dif = 1 + Math.floor((endDate - startDate) / 86400000);
                    input.lay.days = dif > 0 ? dif : null;
                }
            }
        }
    }

    function setResult() {
        if( input && input.lay ) {
            input.lay.production = null;
            if ( input.lay.days && input.lay.eggs && pair.dames) {
                input.lay.production = Math.round(getProduction( input.lay.days, input.lay.eggs, pair.dames ) ); //eggs * 274 / days / dames;
            }
        }
        console.log( 'ILP', input.lay.days, input.lay.eggs, pair.dames, input.lay.production );
    }

    function update() { // pair
        console.log( 'L init', pair );
        if( input !== pair ) {
            input = pair;
            if (!input.lay) {
                input.lay = {start: null, end: null, eggs: null, dames: null, weight: null}
            }
        }
    }

    function validate() { // input
        console.log( 'L val');
        setDays( input.lay );
        setResult( input.lay );
        invalid =
            ( input.lay.start && ! input.lay.days ) ||
            ( input.lay.start && ! input.lay.eggs ) ||
            input.lay.eggs < 0 || input.lay.eggs > (input.lay.days * pair.dames) ;
        // ||input.lay.days < 0 || input.lay.days > 365 || input.lay.result < 0 || input.lay.result > 366;

        pair = input;
    }


    $: update( pair );
    $: validate( input );


</script>

<div class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Legeleistung</div>
        <div class='w-6'>{invalid}</div>
    </div>

    {#if pair.lay }
        <div class='flex flex-row p-2 gap-x-1'>
            <div class='grow flex flex-row gap-x-1'>
                <InputDate class='w-24' label={'Gesammelt ab'} bind:value={input.lay.start} {disabled}/>
                <InputDate class='w-24' label={'Gesammelt bis'} bind:value={input.lay.end} min={ input.lay.start } error='Wenigstens einen Tag sammeln!' {disabled}/>
                <InputNumber class='w-16' label={'# Eierzahl'} bind:value={input.lay.eggs} min=0 max={input.lay.days * pair.dames} error='Unmöglich' {disabled} />
                <InputNumber class='w-16' label={'∅ Gewicht'} bind:value={input.lay.weight} min=1 max=999 {disabled} />
            </div>
            <div class='flex flex-row gap-x-1'>
                <InputNumber class='w-16' label='Tagen' value={input.lay.days} disabled readonly/>
                <InputNumber class='w-16' label='# Hennen' value={pair.dames} disabled readonly/>
                <InputText class='w-16' label='Eier / Jahr' value={input.lay.production} disabled readonly />
            </div>
        </div>
    {/if}
</div>

<style>

</style>