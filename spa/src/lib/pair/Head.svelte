<script>

    import {afterUpdate, onMount} from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    import {user} from "../../js/store.js";
    import api from '../../js/api.js';
    import { newReport } from '../../js/template.js';

    import InputDate   from '../common/input/Date.svelte';
    import InputNumber from '../common/input/Number.svelte';
    import InputText   from '../common/input/Text.svelte';
    import InputRing from '../common/input/Ring.svelte';
    import Select from '../common/input/Select.svelte';
    import ReportBreed from './Breed.svelte';
    import ReportBroods from './Broods.svelte';
    import ReportLay from './Lay.svelte';
    import ReportNotes from './Notes.svelte';
    import ReportParents from './Elders.svelte';
    import ReportShow from './Show.svelte';
    import Page from "../common/Page.svelte";

//    export let id;
    export let pair;
    export let disabled = true;
    export let invalid = false;

    let layer = pair.sectionId === 5;

    let needFocus = true;
    let focusElement;

    onMount( () => {
    })

    afterUpdate( () => {
        if( needFocus ) {
            focusElement.focus();
            needFocus = false;
        }
    })

    function setFocus( disabled ) {
        if ( ! disabled && focusElement ) {
            needFocus = true;
        }
    }

    function validate( pair ) {
        invalid = ! pair.year || pair.year < MINYEAR || pair.year > MAXYEAR;
        invalid |= ! pair.name || pair.name.length < 1;
        invalid |= ! pair.group;
        console.log( 'Invalid', invalid, pair);
    }

    $: setFocus( disabled ) // on switch to ! disabled
    $: validate( pair );

</script>


<div class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Stamm / Paar (#{pair.id})</div>
        <div class='w-6'>{invalid}</div>
    </div>


    <div class='flex flex-row p-2 gap-x-1'>
        <InputText class='w-32' label='Züchter' value={pair.breederId} readonly disabled/>
        <InputNumber class='w-20' bind:element={focusElement} label='Jahr' name='year' bind:value={pair.year} min={MINYEAR} max={MAXYEAR} required/>
        <InputText class='w-20' label='Name' bind:value={pair.name} error='Pflichtfeld' spellcheck=false required/>
        <Select class='w-20' label='ZB Gruppe' bind:value={pair.group} placeholder='?' required>
            {#each ['I', 'II', 'III' ] as group}
                <option value={group} selected={group === pair.group}>{group}</option>
            {/each}
        </Select>
    </div>
</div>

<style>
    .disabled {
        @apply text-white;
    }
</style>