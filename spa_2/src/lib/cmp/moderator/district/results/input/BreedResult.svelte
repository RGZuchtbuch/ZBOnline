<script>

    import validator   from '$lib/cmp/form/validator.js';

    import Form, { Status, NumberInput } from '$lib/cmp/form/Form.svelte';
    import { Result } from '$lib/js/result.svelte.js';
    //import NumberInput from '../../common/form/input/NumberInput.svelte';
    //import FormStatus from '../../common/form/Status.svelte';

    import AddResultRow from './AddResultRow.svelte'

    let { section, breed, result } = $props();

    let data = $state( result );
    let hasResult = $derived( data.breeders > 0 );
    let extended = $state( false ); //TODO remove ?

    const validate = {
        breeders     : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        pairs        : (v) => validator(v).number().range( result.breeders, 99999 ).orNull().isValid(),

        lay: {
            dames: (v) => validator(v).number().range(1, 99999).orNull().isValid(),
            eggs: (v) => validator(v).number().range(0, 366).orNull().isValid(),
            weight: (v) => validator(v).number().range(1, 999).orNull().isValid(),
        },
        brood: {
            chicks: (v) => validator(v).number().if(data.pairs > 0).range(0, data.pairs * 50).orNull().isValid(),
            eggs: (v) => validator(v).number().range(1, 99999).orNull().isValid(),
            fertile: (v) => validator(v).number().range(0, data.brood.eggs).orNull().isValid(),
            hatched: (v) => validator(v).number().range(0, data.brood.fertile == null ? data.brood.eggs : data.brood.fertile).orNull().isValid(),
        },
        show: {
            count    : (v) => validator(v).number().range( 1, 99999 ).orNullIf( data.show.score == null ).isValid(),
            score    : (v) => validator(v).number().range( 89, 97 ).orNullIf( data.show.count == null ).isValid(),
        },

    }

    // function onToggleExtend( event ) {
    //     extended = ! extended;
    // }

    async function onSubmit( event ) {
        console.log( 'Submit color result' );
        if( data.breeders ) { // valid entry
            return await Result.save( data );
        } else { // delete if no breeders count given
            if( data.id > 0 ) {
                return await Result.delete( data.id );
            }
        }
    }

    // function onChange( result ) {
    //     console.log( 'Result', result );
    //     dispatch( 'change', result );
    // }
</script>

<Form class='flex flex-row px-2 gap-x-1 text-sm' autosubmit onsubmit={onSubmit}>
    <div class='w-4 ml-6'>&#10551; </div>
    <div class='w-80 flex flex-row justify-between'>
        <div class='' class:hasResult title={'Leistung ['+data.id+']'}> Gesamte Farbenschläge </div>
        <!--button class='self-start w-6' type='button' title='Hinzufügen' on:click={onToggleExtend}>&#43;</button -->
    </div>

    <NumberInput class='w-14' bind:value={data.breeders} error='1..99999' title='Zahl der Zuchten/Züchter, leer lassen zum Löschen' validator={validate.breeders} />
    <NumberInput class='w-14' bind:value={data.pairs}  error={ (data.breeders ? data.breeders : '1')+'..99999'} title='Zahl der Stämme/Paare' validator={validate.pairs} />
    <div class='w-2'></div>
    <!-- lay -->
    <div class='w-14'></div> <div class='w-14'></div> <!-- div class='w-14' / -->
    <div class='w-2'></div>
    <!-- brood -->
    <div class='w-14'></div>
    <NumberInput class='w-14' bind:value={data.brood.hatched} error='0..99999' title='Geschlüpfte Küken, Braucht Paare' validator={validate.brood.chicks}/>
    <div class='w-14'></div>
    <div class='w-2'></div>
    <!-- show -->
    <NumberInput class='w-14' bind:value={data.show.count} error='1..99999' title='Zahl der ausgestellten Tiere' validator={validate.show.count}/>
    <NumberInput class='w-14' bind:value={data.show.score} step={0.1} error='89..97' title='Durchschnittsbewertung u/o=89, 90..97 Punkte, braucht Zahl der ausgestellen Tiere' validator={validate.show.score}/>


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