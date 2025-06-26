<script>

    import store from '$lib/js/store.svelte.js';
    import Form, { Status, NumberInput, validator } from '$lib/cmp/form/Form.svelte';
    //import NumberInput from '../../common/form/input/NumberInput.svelte';
    //import FormStatus from '../../common/form/Status.svelte';
    import { Result } from '$lib/js/result.svelte.js';

    import AddResultRow from './AddResultRow.svelte'
    import {invalidate} from '$app/navigation';

    let { section, result } = $props();

    $inspect( 'AOC R', section, result );

    let data = $state( result );
    let breed = $derived( store.standard.breeds[ result.breedId ] );
    let color = $derived( store.standard.colors[ result.colorId ] );
    let hasResult = $derived( data.breeders > 0 );
    let extended = $state( false ); //TODO remove ?

    //const dispatch = createEventDispatcher();

    //result.sectionId = section.id;

    const validate = {
        breeders     : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
        pairs        : (v) => validator(v).number().range( data.breeders, 99999 ).orNull().isValid(),

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
            count: (v) => validator(v).number().range(1, 99999).orNullIf(data.show.score == null).isValid(),
            score: (v) => validator(v).number().range(89, 97).orNullIf(data.show.count == null).isValid(),
        }
    }

    async function onSubmit( event ) {
        console.log( 'Submit color result' );
        await invalidate( 'results' ); // make results page reload data
        if( data.breeders ) { // valid entry
            return await Result.save( data );
        } else { // delete if no breeders count given
            if( data.id > 0 ) {
                return await Result.delete( data.id );
            }
        }
    }

</script>

<Form class='flex flex-col px-2 gap-x-1 text-sm' autosubmit onsubmit={onSubmit}>
    <div>{breed.name}</div>
    <div class='flex flex-row px=2 gap-x-1 text-sm'>
        <div class='w-4 pl-2'>&#10551; </div>
        <div class='w-80 flex flex-col justify-between'>
            <div class='' class:hasResult title={'Leistung ['+result.id+']'}>{result.aocColor}</div>
            <!--button class='self-start w-6' type='button' title='Hinzufügen' on:click={onToggleExtend}>&#43;</button -->
        </div>

        <NumberInput class='w-14' label='Zuchten' bind:value={data.breeders} error='1..99999' title='Zahl der Zuchten/Züchter, leer lassen zum Löschen' validator={validate.breeders} />
        {#if data.sectionId === 5}
            <NumberInput class='w-14' label='Paare' bind:value={data.pairs}  error={ (data.breeders ? data.breeders : '1')+'..99999'} title='Zahl der Stämme/Paare' validator={validate.pairs} />
            <div class='w-2'></div>
            <!-- lay -->
            <div class='w-14'></div> <div class='w-14'></div> <!-- div class='w-14' / -->
            <div class='w-2'></div>
            <!-- brood -->
            <div class='w-14'></div>
            <NumberInput class='w-14' label='Küken' bind:value={data.brood.hatched} error='0..99999' title='Geschlüpfte Küken, Braucht Paare' validator={validate.brood.chicks}/>
            <div class='w-14'></div>

        {:else}
            <div class='w-14'></div>
            <div class='w-2'></div>
            <!-- lay -->
            <!-- NumberInput class='w-14' bind:value={result.layDames} error='0..99999' title='Gesamtzahl der legende Hennen' validator={validate.layDames}/ -->
            <NumberInput class='w-14' label='Eier' bind:value={data.lay.eggs} error='0..366' title='Durchschnittslegeleistung' validator={validate.lay.eggs}/>
            <NumberInput class='w-14' label='Gewicht' bind:value={data.lay.weight} error='1..999' title='Durchschnittsgewicht der gelegten Eier' validator={validate.lay.weight}/>

            <div class='w-2'></div>
            <!-- brood -->
            <NumberInput class='w-14' label='Eingelegt' bind:value={data.brood.eggs}    error='0..99999' title='Eigelegte Eier' validator={validate.brood.eggs}/>
            <NumberInput class='w-14' label='Befruchtet' bind:value={data.brood.fertile} error='0..{data.brood.eggs}' title='Befruchtete Eier, nicht mehr als eingelegt' validator={validate.brood.fertile}/>
            <NumberInput class='w-14' label='Geschlüpft' bind:value={data.brood.hatched} error='0..{data.brood.fertile==null ? data.brood.eggs : data.brood.fertile}' title='Geschlüpfte Küken, nicht mehr als befruchtet oder eingelegt' validator={validate.brood.hatched}/>


        {/if}
        <div class='w-2'></div>
        <!-- show -->
        <NumberInput class='w-14' label='Tiere' bind:value={data.show.count} error='1..99999' title='Zahl der ausgestellten Tiere' validator={validate.show.count}/>
        <NumberInput class='w-14' label='Note' bind:value={data.show.score} step={0.1} error='89..97' title='Durchschnittsbewertung u/o=89, 90..97 Punkte, braucht Zahl der ausgestellen Tiere' validator={validate.show.score}/>

        <Status class='w-4' />
    </div>
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