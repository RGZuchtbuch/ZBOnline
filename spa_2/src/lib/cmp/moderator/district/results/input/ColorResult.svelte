<script>
    import { invalidate } from '$app/navigation';
    import model from '$lib/js/model.js';
    import Form, { Status, NumberInput, validator } from '$lib/cmp/form/Form.svelte';
    //import NumberInput from '../../common/form/input/NumberInput.svelte';
    //import FormStatus from '../../common/form/Status.svelte';

    import AddResultRow from './AddResultRow.svelte'

    let { result, data } = $props();

    let resultState = $derived( result );
    let hasResult = $derived( resultState.breeders > 0 );
    let extended = $state( false ); //TODO remove ?

    //const dispatch = createEventDispatcher();

    //result.sectionId = section.id;

    const validate = {
        breeders     : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        pairs        : (v) => validator(v).number().range( resultState.breeders, 99999 ).orNull().isValid(),

        lay: {
            dames: (v) => validator(v).number().range(1, 99999).orNull().isValid(),
            eggs: (v) => validator(v).number().range(0, 366).orNull().isValid(),
            weight: (v) => validator(v).number().range(1, 999).orNull().isValid(),
        },
        brood: {
            chicks: (v) => validator(v).number().if(resultState.pairs > 0).range(0, resultState.pairs * 50).orNull().isValid(),
            eggs: (v) => validator(v).number().range(1, 99999).orNull().isValid(),
            fertile: (v) => validator(v).number().range(0, resultState.brood.eggs).orNull().isValid(),
            hatched: (v) => validator(v).number().range(0, resultState.brood.fertile == null ? resultState.brood.eggs : resultState.brood.fertile).orNull().isValid(),
        },
        show: {
            count: (v) => validator(v).number().range(1, 99999).orNullIf(resultState.show.score == null).isValid(),
            score: (v) => validator(v).number().range(89, 97).orNullIf(resultState.show.count == null).isValid(),
        }
    }

    // function onToggleExtend( event ) {
    //     extended = ! extended;
    // }

    async function onSubmit( event ) {
        console.log( 'Submit color result' );
        console.log( 'RState', result, resultState );
        let response = null;
        if( resultState.breeders > 0 ) { // valid entry
            response = await model.Result.save( resultState );
        } else { // delete if no breeders count given
            if( resultState.id > 0 ) {
                response = await model.Result.delete( resultState.id );
            }
        }
        //await invalidate( 'results' ); // make results page reload data
        return response;

    }

    // function onChange( result ) {
    //     console.log( 'Result', result );
    //     dispatch( 'change', result );
    // }
</script>

<Form class='flex flex-row bg-gray-50 px-2 gap-x-1 text-sm' autosubmit onsubmit={onSubmit}>
    <div class='w-4 ml-6'> &#10551; </div>
    <div class='w-80 flex flex-row justify-between'>
        <div class='' class:hasResult title={'Leistung ['+result.id+']'}> {resultState.colorName} {resultState.id}</div>
        <!--button class='self-start w-6' type='button' title='Hinzufügen' on:click={onToggleExtend}>&#43;</button -->
    </div>

    <NumberInput class='w-14' bind:value={resultState.breeders} error='1..99999' title='Zahl der Zuchten/Züchter, leer lassen zum Löschen' validator={validate.breeders} />
    <div class='w-14'></div>

    <div class='w-2'></div>
    <!-- lay -->
    <!-- NumberInput class='w-14' bind:value={result.layDames} error='0..99999' title='Gesamtzahl der legende Hennen' validator={validate.layDames}/ -->
    <NumberInput class='w-14' bind:value={resultState.lay.eggs} error='0..366' title='Durchschnittslegeleistung' validator={validate.lay.eggs}/>
    <NumberInput class='w-14' bind:value={resultState.lay.weight} error='1..999' title='Durchschnittsgewicht der gelegten Eier' validator={validate.lay.weight}/>

    <div class='w-2'></div>
    <!-- brood -->
    <NumberInput class='w-14' bind:value={resultState.brood.eggs} error='0..99999' title='Eigelegte Eier' validator={validate.brood.eggs}/>
    <NumberInput class='w-14' bind:value={resultState.brood.fertile} error='0..{resultState.brood.eggs}' title='Befruchtete Eier, nicht mehr als eingelegt' validator={validate.brood.fertile}/>
    <NumberInput class='w-14' bind:value={resultState.brood.hatched} error='0..{resultState.brood.fertile==null ? resultState.brood.eggs : resultState.brood.fertile}' title='Geschlüpfte Küken, nicht mehr als befruchtet oder eingelegt' validator={validate.brood.hatched}/>

    <div class='w-2'></div>

    <NumberInput class='w-14' bind:value={resultState.show.count} error='1..99999' title='Zahl der ausgestellten Tiere' validator={validate.showcount}/>
    <NumberInput class='w-14' bind:value={resultState.show.score} step={0.1} error='89..97' title='Durchschnittsbewertung u/o=89, 90..97 Punkte, braucht Zahl der ausgestellen Tiere' validator={validate.show.score}/>


    <Status class='w-4' />
</Form>

{#if extended}
    <!--AddResultRow {sectionId} bind:result={result} on:add={onAdd}/-->
{/if}


<style>
    .hasResult {
        @apply font-bold;
    }
    input[type='button'] {
        @apply text-white m-0 p-0;
    }

    button {
        vertical-align: text-top;
    }
</style>