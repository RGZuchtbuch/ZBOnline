<script>
    import { createEventDispatcher } from 'svelte';
    import { slide } from 'svelte/transition';
    import api         from '../../../js/api.js';
    import validator   from '../../../js/validator.js';

    import Form from '../../common/form/Form.svelte';
    import NumberInput from '../../common/form/input/NumberInput.svelte';
    import FormStatus from '../../common/form/Status.svelte';
    import Submit from '../../common/form/Submit.svelte';

    export let sectionId; // for pigeons or other
    export let result;

    let hasResult = false;

    const dispatch = createEventDispatcher();

    let addedResult = newResult();


    const validate = {
        breeders     : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        pairs        : (v) => validator(v).number().range( addedResult.breeders, 99999 ).orNull().isValid(),

        layDames     : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        layEggs      : (v) => validator(v).number().range( 0, 366 ).orNull().isValid(),
        layWeight    : (v) => validator(v).number().range( 1, 999 ).orNull().isValid(),

        broodChicks  : (v) => validator(v).number().if( addedResult.pairs > 0).range( 0, addedResult.pairs * 50 ).orNull().isValid(),
        broodEggs    : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        broodFertile : (v) => validator(v).number().range( 0, addedResult.broodEggs ).orNull().isValid(),
        broodHatched : (v) => validator(v).number().range( 0, addedResult.broodFertile == null ? addedResult.broodEggs : addedResult.broodFertile ).orNull().isValid(),

        showCount    : (v) => validator(v).number().range( 1, 99999 ).orNullIf( addedResult.showScore == null ).isValid(),
        showScore    : (v) => validator(v).number().range( 89, 97 ).orNullIf( addedResult.showCount == null ).isValid(),

    }

    function newResult() {
        return {
            breeders: null,
            pairs: null,
            layEggs: null,
            layWeight: null,
            broodEggs: null,
            broodFertile: null,
            broodHatched: null,
            showCount: null,
            showScore: null
        }
    }

    function onSubmit( event ) {
        console.log('Result add A', addedResult, result );
        // these before changing breeders


        dispatch( 'add', addedResult );

        console.log('Result add B', addedResult, result );
        addedResult = newResult();
    }



    $: hasResult = addedResult.breeders > 0;


</script>

<div transition:slide>
<Form class='flex flex-row ml-48 m-4 border rounded border-gray-800 bg-gray-300 pt-2 px-2 gap-x-1 text-sm' on:submit={onSubmit} autoSave={false}>
    <div class='w-0'></div>
    <div class='w-36 flex flex-row justify-end'>
        <div class='' title={'Leistung hinzufügen'}>Hinzufügen</div>
        <div class='w-6' ></div>
    </div>

    <NumberInput class='w-14' bind:value={addedResult.breeders} error='1..99999' title='Zahl der Zuchten/Züchter, leer lassen zum Löschen' validator={validate.breeders} />
    {#if sectionId === PIGEONS}
        <NumberInput class='w-14' bind:value={addedResult.pairs}  error={ (addedResult.breeders ? addedResult.breeders : '1')+'..99999'} title='Zahl der Stämme/Paare' validator={validate.pairs} />
    {:else}
        <div class='w-14'></div>
    {/if}

    <div class='w-2'></div>
    <!-- lay -->
    {#if sectionId === PIGEONS}
        <div class='w-14'></div> <div class='w-14'></div> <!-- div class='w-14' / -->
    {:else}
        <!-- NumberInput class='w-14' bind:value={result.layDames} error='0..99999' title='Gesamtzahl der legende Hennen' validator={validate.layDames}/ -->
        <NumberInput class='w-14' bind:value={addedResult.layEggs} error='0..366' title='Durchschnittslegeleistung' validator={validate.layEggs}/>
        <NumberInput class='w-14' bind:value={addedResult.layWeight} error='1..999' title='Durchschnittsgewicht der gelegten Eier' validator={validate.layWeight}/>
    {/if}

    <div class='w-2'></div>
    <!-- brood -->
    {#if sectionId === PIGEONS}
        <div class='w-14'></div>
        <NumberInput class='w-14' bind:value={addedResult.broodHatched} error='0..99999' title='Geschlüpfte Küken, Braucht Paare' validator={validate.broodChicks}/>
        <div class='w-14'></div>
    {:else}
        <NumberInput class='w-14' bind:value={addedResult.broodEggs}    error='0..99999' title='Eigelegte Eier' validator={validate.broodEggs}/>
        <NumberInput class='w-14' bind:value={addedResult.broodFertile} error='0..{addedResult.broodEggs}' title='Befruchtete Eier, nicht mehr als eingelegt' validator={validate.broodFertile}/>
        <NumberInput class='w-14' bind:value={addedResult.broodHatched} error='0..{addedResult.broodFertile==null ? addedResult.broodEggs : addedResult.broodFertile}' title='Geschlüpfte Küken, nicht mehr als befruchtet oder eingelegt' validator={validate.broodHatched}/>
    {/if}

    <div class='w-2'></div>

    <NumberInput class='w-14' bind:value={addedResult.showCount} error='1..99999' title='Zahl der ausgestellten Tiere' validator={validate.showCount}/>
    <NumberInput class='w-14' bind:value={addedResult.showScore} step={0.1} error='89..97' title='Durchschnittsbewertung u/o=89, 90..97 Punkte, braucht Zahl der ausgestellen Tiere' validator={validate.showScore}/>
    <Submit class='mb-4' noChangeValue='[?]' submitValue='[+]' invalidValue='[x]' errorValue='[x]'/>
</Form>
</div>


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