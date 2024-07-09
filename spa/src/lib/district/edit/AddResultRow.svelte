<script>
    import { createEventDispatcher } from 'svelte';
    import { slide } from 'svelte/transition';
    import {dec, getDays, getProduction } from '../../../js/util.js';
    import validator from '../../../js/validator.js';

    import Form from '../../common/form/Form.svelte';
    import DateInput from '../../common/form/input/DateInput.svelte';
    import NumberInput from '../../common/form/input/NumberInput.svelte';
    import Submit from '../../common/form/Submit.svelte';

    export let sectionId; // for pigeons or other
    export let result;

    let showLayPeriod = false;

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
            breeders: 1,
            pairs: null,
            layPeriod: newLayPeriod(),
            layEggs: null,
            layWeight: null,
            broodEggs: null,
            broodFertile: null,
            broodHatched: null,
            showCount: null,
            showScore: null
        }
    }
    function newLayPeriod() {
        return { first:null, last:null, days:null, dames:null, eggs:null, production:null };
    }

    function onSubmit( event ) {
        console.log('Result add A', addedResult, result );
        // these before changing breeders
        dispatch( 'add', addedResult );
        console.log('Result add B', addedResult, result );
        addedResult = newResult();
    }

    function onToggleLayPeriod( event ) {
        console.log('onToggleLayPeriod');
        showLayPeriod = ! showLayPeriod;
    }

    function calcPeriodDays( first, last ) {
        console.log( 'Calc days' );
        addedResult.layPeriod.days = getDays( first, last )
    }

    function calcProduction( event ) {
        console.log( 'calcProduction' );
        addedResult.layPeriod.production = Math.round( getProduction( addedResult.layPeriod.days, addedResult.layPeriod.eggs, addedResult.layPeriod.dames ) );
    }

    function onInsertProduction( event ) {
        console.log( 'onInsert' );
        showLayPeriod = false;
        addedResult.layEggs = addedResult.layPeriod.production;
        addedResult.LayPeriod = newLayPeriod();
    }

    $: calcPeriodDays( addedResult.layPeriod.first, addedResult.layPeriod.last );
    $: calcProduction( addedResult.layPeriod.days, addedResult.layPeriod.eggs, addedResult.layPeriod.dames )


</script>

<div class='flex flex-col' transition:slide>
    <Form class='flex flex-col ml-48 mr-4 mb-4 border rounded border-gray-800 bg-gray-300 pt-2 px-2 text-sm' on:submit={onSubmit} autoSave={false}>
        <div class='flex flex-row gap-x-1 '>
            <div class='w-0'></div>
            <div class='w-36 flex flex-row justify-end'>
                <div class='' title={'Leistung hinzufügen'}>Hinzufügen</div>
                <div class='w-6' ></div>
            </div>

            <NumberInput class='w-14' bind:value={addedResult.breeders} error='1..99999' title='Zahl der Zuchten/Züchter, leer lassen zum Löschen' validator={validate.breeders} />
            {#if sectionId === PIGEONS}
                <NumberInput class='w-14' bind:value={addedResult.pairs}  error={ (addedResult.breeders ? addedResult.breeders : '1')+'..99999'} title='Zahl der Stämme/Paare' validator={validate.pairs} />
                <div class='w-2'></div>
            {:else}
                <div class='w-2'></div>
                <div class='w-14 flex flex-row justify-end'>
                    <button class='w-6 mb-4' type='button' title='Legeleistung berechnen' on:click={onToggleLayPeriod}>&#43;</button>
                </div>

            {/if}

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
            <Submit class='mb-4' noChangeValue='&#63;' submitValue='&#43;' invalidValue='&#10539;' errorValue='[x]'/>
        </div>
        {#if showLayPeriod}
            <div class='flex flex-rol gap-x-1'>
                <div class='w-0'></div>
                <div class='w-36 flex flex-row justify-end'>
                    <div class='mt-5' title={'Legeleistungsrechner'}>Berechnen Eier/J</div>
                    <div class='w-6' ></div>
                </div>

                <DateInput class='w-24' label='Ab' bind:value={addedResult.layPeriod.first}></DateInput>
                <DateInput class='w-24' label='Bis' bind:value={addedResult.layPeriod.last}></DateInput>
                <div class='w-12 my-5 px-1 border border-gray-400 text-right'>{dec(addedResult.layPeriod.days)}</div>
                <div class='mt-5'>Tage</div>

                <NumberInput class='w-14' label='Hennen' bind:value={addedResult.layPeriod.dames} error='1..99' title='Zahl der Hennen' validator={validate.layEggs} />
                <NumberInput class='w-14' label='Eier' bind:value={addedResult.layPeriod.eggs} error='1..366' title='Gesammelte Eier' validator={validate.layEggs} />
                <div class='mt-5'> → </div>
                <div class='w-10 my-5 px-1 border border-gray-400 text-right'>{dec(addedResult.layPeriod.production)}</div>
                <div class='my-5'>Eier / Jahr</div>
                <button class='my-5 bg-button px-1 text-white' type='button' on:click={onInsertProduction}>Einfügen</button>
            </div>
        {/if}
    </Form>

</div>


<style>
</style>