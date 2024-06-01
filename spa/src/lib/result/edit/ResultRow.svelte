<script>
    import { slide } from 'svelte/transition';
    import api         from '../../../js/api.js';
    import validator   from '../../../js/validator.js';

    import Form from '../../common/form/Form.svelte';
    import NumberInput from '../../common/form/input/NumberInput.svelte';
    import FormStatus from '../../common/form/Status.svelte';

    import AddResultRow from './AddResultRow.svelte'

    export let sectionId;
    export let result;

    let hasResult = false;
    let extended = false;

    result.sectionId = sectionId;

    const validate = {
        breeders     : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        pairs        : (v) => validator(v).number().range( result.breeders, 99999 ).orNull().isValid(),

        layDames     : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        layEggs      : (v) => validator(v).number().range( 0, 366 ).orNull().isValid(),
        layWeight    : (v) => validator(v).number().range( 1, 999 ).orNull().isValid(),

        broodChicks  : (v) => validator(v).number().if( result.pairs > 0).range( 0, result.pairs * 50 ).orNull().isValid(),
        broodEggs    : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        broodFertile : (v) => validator(v).number().range( 0, result.broodEggs ).orNull().isValid(),
        broodHatched : (v) => validator(v).number().range( 0, result.broodFertile == null ? result.broodEggs : result.broodFertile ).orNull().isValid(),

        showCount    : (v) => validator(v).number().range( 1, 99999 ).orNullIf( result.showScore == null ).isValid(),
        showScore    : (v) => validator(v).number().range( 89, 97 ).orNullIf( result.showCount == null ).isValid(),

    }

    function onToggleExtend( event ) {
        extended = ! extended;
    }

    function onSubmit( event ) {
        console.log('Result submit', result );
        if( result.breeders ) { // valid entry
            if( result.id > 0 ) { // existing
                api.result.put( result.id, result ).then((response) => {
                    result.id = response.id; // new id when inserted
                });
            } else {
                api.result.post(result).then((response) => {
                    result.id = response.id; // new id when inserted
                });
            }
        } else { // delete if no breeders count given
            if( result.id > 0 ) {
                api.result.delete(result.id).then((response) => {
                    result.id = null; // does not exist anymore, but still showing for new edit
                });
            }
        }
    }
    function onAdd( event ) {
        extended = false; // hide
        const addResult = event.detail;
        console.log( 'toAdd', addResult );
        result.pairs        = add( addResult.pairs, result.pairs );
        result.layEggs      = avg( addResult.breeders, addResult.layEggs, result.breeders, result.layEggs );
        result.layWeight    = avg( addResult.breeders, addResult.layWeight, result.breeders, result.layWeight );
        result.broodEggs    = add( addResult.broodEggs, result.broodEggs );
        result.broodFertile = add( addResult.broodFertile, result.broodFertile );
        result.broodHatched = add( addResult.broodHatched, result.broodHatched );
        result.showCount    = add( addResult.showCount, result.showCount );
        result.showScore    = avg( addResult.breeders, addResult.showScore, result.breeders, result.showScore );
        // breeders must be last as old value is needed for avg
        result.breeders     = add( addResult.breeders, result.breeders );
        onSubmit( null );
    }

    function add( value, toValue ) {
        return value ? toValue + value : toValue;
    }
    function avg( addCount, addValue, toCount, toValue ) {
        return addValue ? (( toCount * toValue + addCount * addValue ) / ( toCount + addCount )).toFixed(1) : toValue; // avg
    }

    $: hasResult = result.breeders > 0;


</script>

<Form class='flex flex-row px-2 gap-x-1 text-sm' on:submit={onSubmit}>
    <div class='w-4 pl-2'>&#10551; </div>
    <div class='w-80 flex flex-row justify-between'>
        <div class='' class:hasResult title={'Leistung ['+result.id+']'}>{result.colorName} </div>
        <button class='self-start w-6' type='button' on:click={onToggleExtend}>[+]</button>
    </div>


    <NumberInput class='w-14' bind:value={result.breeders} error='1..99999' title='Zahl der Zuchten/Züchter, leer lassen zum Löschen' validator={validate.breeders} />
    {#if sectionId === PIGEONS}
        <NumberInput class='w-14' bind:value={result.pairs}  error={ (result.breeders ? result.breeders : '1')+'..99999'} title='Zahl der Stämme/Paare' validator={validate.pairs} />
    {:else}
        <div class='w-14'></div>
    {/if}

    <div class='w-2'></div>
    <!-- lay -->
    {#if sectionId === PIGEONS}
        <div class='w-14'></div> <div class='w-14'></div> <!-- div class='w-14' / -->
    {:else}
        <!-- NumberInput class='w-14' bind:value={result.layDames} error='0..99999' title='Gesamtzahl der legende Hennen' validator={validate.layDames}/ -->
        <NumberInput class='w-14' bind:value={result.layEggs} error='0..366' title='Durchschnittslegeleistung' validator={validate.layEggs}/>
        <NumberInput class='w-14' bind:value={result.layWeight} error='1..999' title='Durchschnittsgewicht der gelegten Eier' validator={validate.layWeight}/>
    {/if}

    <div class='w-2'></div>
    <!-- brood -->
    {#if sectionId === PIGEONS}
        <div class='w-14'></div>
        <NumberInput class='w-14' bind:value={result.broodHatched} error='0..99999' title='Geschlüpfte Küken, Braucht Paare' validator={validate.broodChicks}/>
        <div class='w-14'></div>
    {:else}
        <NumberInput class='w-14' bind:value={result.broodEggs}    error='0..99999' title='Eigelegte Eier' validator={validate.broodEggs}/>
        <NumberInput class='w-14' bind:value={result.broodFertile} error='0..{result.broodEggs}' title='Befruchtete Eier, nicht mehr als eingelegt' validator={validate.broodFertile}/>
        <NumberInput class='w-14' bind:value={result.broodHatched} error='0..{result.broodFertile==null ? result.broodEggs : result.broodFertile}' title='Geschlüpfte Küken, nicht mehr als befruchtet oder eingelegt' validator={validate.broodHatched}/>
    {/if}

    <div class='w-2'></div>

    <NumberInput class='w-14' bind:value={result.showCount} error='1..99999' title='Zahl der ausgestellten Tiere' validator={validate.showCount}/>
    <NumberInput class='w-14' bind:value={result.showScore} step={0.1} error='89..97' title='Durchschnittsbewertung u/o=89, 90..97 Punkte, braucht Zahl der ausgestellen Tiere' validator={validate.showScore}/>
    <FormStatus class='w-4' />
</Form>

{#if extended}
    <AddResultRow {sectionId} bind:result={result} on:add={onAdd}/>
{/if}


<style>
    .hasResult {
        @apply font-bold;
    }
    input[type='button'] {
        @apply bg-alert text-white m-0 p-0;
    }

    button {
        vertical-align: text-top;
    }
</style>