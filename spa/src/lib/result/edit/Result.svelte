<script>
    import { slide } from 'svelte/transition';
    import api         from '../../../js/api.js';
    import validator   from '../../../js/validator.js';

    import Form from '../../common/form/Form.svelte';
    import NumberInput from '../../common/form/input/NumberInput.svelte';
    import FormStatus from '../../common/form/Status.svelte';
    import NumberInputOld from '../../common/input/Number.svelte';

    export let sectionId = null;
    export let result = null;

    let hasResult = false;

    const validate = {
        breeders     : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        pairs        : (v) => validator(v).number().range( result.breeders, 99999 ).orNull().isValid(),

        layDames     : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        layEggs      : (v) => validator(v).number().range( 0, 366 ).orNull().isValid(),
        layWeight    : (v) => validator(v).number().range( 1, 999 ).orNull().isValid(),

        broodEggs    : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        broodFertile : (v) => validator(v).number().range( 0, result.broodEggs ).orNull().isValid(),
        broodHatched : (v) => validator(v).number().range( 0, result.broodFertile == null ? result.broodEggs : result.broodFertile ).orNull().isValid(),

        showCount    : (v) => validator(v).number().range( 1, 99999 ).orNullIf( result.showScore == null ).isValid(),
        showScore    : (v) => validator(v).number().range( 89, 97 ).orNullIf( result.showCount == null ).isValid(),

    }


    function onSubmit( event ) {
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

    $: hasResult = result.breeders > 0;

</script>

<Form class='flex flex-row px-2 gap-x-1 text-sm' on:submit={onSubmit}>
    <div class='w-4 pl-2'>&#10551; </div>
    <div class='w-80' class:hasResult title={'Leistung ['+result.id+']'}>{result.name}</div>

    <NumberInput class='w-14' bind:value={result.breeders} error='1..99999' title='Zahl der Zuchten/Züchter, leer lassen zum Löschen' validator={validate.breeders} />
    <NumberInput class='w-14' bind:value={result.pairs}  error={ (result.breeders ? result.breeders : '1')+'..99999'} title='Zahl der Stämme/Paare' validator={validate.pairs} />

    <div class='w-2'></div>

    {#if sectionId === PIGEONS}
        <div class='w-14'></div> <div class='w-14'></div> <div class='w-14'></div>
    {:else}
        <NumberInput class='w-14' bind:value={result.layDames} error='0..99999' title='Gesamtzahl der legende Hennen' validator={validate.layDames}/>
        <NumberInput class='w-14' bind:value={result.layEggs} error='0..366' title='Durchschnittslegeleistung' validator={validate.layEggs}/>
        <NumberInput class='w-14' bind:value={result.layWeight} error='1..999' title='Durchschnittsgewicht der gelegten Eier' validator={validate.layWeight}/>
    {/if}

    <div class='w-2'></div>

    {#if sectionId === PIGEONS}
        <div class='w-14'></div>
        <NumberInput class='w-14' bind:value={result.broodHatched} error='0..999999' title='Geschlüpfte Küken, Braucht Paare' validator={validate.broodHatched}/>
        <div class='w-14'></div>
    {:else}
        <NumberInput class='w-14' bind:value={result.broodEggs}    error='0..99999' title='Eigelegte Eier' validator={validate.broodEggs}/>
        <NumberInput class='w-14' bind:value={result.broodFertile} error='0..{result.broodEggs}' title='Befruchtete Eier, nicht mehr als eingelegt' validator={validate.broodFertile}/>
        <NumberInput class='w-14' bind:value={result.broodHatched} error='0..{result.broodFertile==null ? result.broodEggs : result.broodFertile}' title='Geschlüpfte Küken, nicht mehr als befruchtet oder eingelegt' validator={validate.broodHatched}/>
    {/if}

    <div class='w-2'></div>

    <NumberInput class='w-14' bind:value={result.showCount} error='1..99999' title='Zahl der ausgestellten Tiere' validator={validate.showCount}/>
    <NumberInput class='w-14' bind:value={result.showScore} step={0.1} error='89..97' title='Durchschnittsbewertung u/o=89, 90..97 Punkte, braucht Zahl der ausgestellen Tiere' validator={validate.showScore}/>
    <FormStatus class='w-4'/>
</Form>


<style>
    .hasResult {
        @apply font-bold;
    }
    input[type='button'] {
        @apply bg-alert text-white m-0 p-0;
    }
</style>