<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    import api from '../js/api.js';
    import { newReport } from '../js/template.js';

    import InputDate   from './input/Date.svelte';
    import InputNumber from './input/Number.svelte';
    import InputText   from './input/Text.svelte';
    import InputRing from './input/Ring.svelte';
    import Select from './input/Select.svelte';
    import ReportBreed from './report/Breed.svelte';
    import ReportBroods from './report/Broods.svelte';
    import ReportLay from './report/Lay.svelte';
    import ReportNotes from './report/Notes.svelte';
    import ReportParents from './report/Parents.svelte';
    import ReportShow from './report/Show.svelte';

    export let reportId;
    export let legend = '';
    export let link='';

    let report = null;
    let layer = true;

    let disabled = true;
    const groups = ['I', 'II', 'III' ];

    function onEdit() {
        disabled = false;
    }

    function onSubmit() {
        disabled = true;
        console.log('Submit', report);
        api.report.post( report )
            .then( response => {
                report.id = response.id;
                report = report;
                console.log('Success', response);
            });
    }

    function loadReport( reportId ) {
        console.log( 'Load report ', reportId, Number.isInteger( reportId ), typeof reportId ==='string' );
        if( reportId ) { // existing
            api.report.get( reportId ).then( response => {
                report = response.report;
            });
        } else { // new
            report = newReport();

        }
    }

    function update( report ) {
        let count = 0;
        if( report ) {
            console.log( 'Update report', report.parents.length );
            for( let parent of report.parents ) {
                if( parent.sex === '0.1' ) count++;
            }
            report.lay.dames = count;

            layer = report.sectionId !== 5;
            report = report;
        }
    }

    $: loadReport( reportId );
    $: update( report );

    onMount( () => {

    })



</script>


<h2 class='text-center'>Zuchtbuch Meldung</h2>

<form class='flex flex-col bg-gray-100 overflow-y-scroll border rounded border-gray-600 scrollbar' on:submit|preventDefault={onSubmit}>
    {#if report}

        <div class='flex flex-col'>
            <h4 class='bg-gray-300'>Stamm {report.id}</h4>
            <div class='flex flex-row gap-x-1'>
                <InputText class='w-32' label='Züchter' value={report.breederId} readonly {disabled}/>
                <InputNumber class='w-16' label='Jahr' name='year' bind:value={report.year} min='1850' max='2030' {disabled}/>
                <InputText class='w-16' label='Name' bind:value={report.name} spellcheck=false {disabled} required/>
                <Select class='w-12' label='Gruppe' options={groups} bind:value={report.group} placeholder='?' {disabled} required>
                    {#each groups as group}
                        <option value={group} selected={group === report.group}>{group}</option>
                    {/each}
                </Select>
            </div>
        </div>


        <ReportBreed bind:breed={report} {disabled}/>

        {#if report.sectionId }

            <ReportParents bind:paired={report.paired} bind:parents={report.parents} {layer} {disabled}/>

            {#if report.sectionId !== 5}
                <ReportLay bind:lay={report.lay} {disabled}/>
            {/if}

            <ReportBroods bind:broods={report.broods} {layer} {disabled} />

            <ReportShow bind:show={report.show} {disabled} />

            <ReportNotes bind:notes={report.notes} {disabled} />

        {/if}

        {#if disabled}
            <button type='button' class='rounded border bg-gray-500 text-center text-white cursor-pointer' on:click={onEdit}>Meldung beartbeiten</button>
        {:else}
            <button type='submit' class='rounded border bg-gray-500 text-center text-white cursor-pointer'>Meldung speichern</button>
        {/if}
    {/if}
</form>

<style>

</style>