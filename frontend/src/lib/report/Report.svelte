<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    import api from '../../js/api.js';

    import InputDate   from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputText   from '../input/Text.svelte';
    import InputRing from '../input/Ring.svelte';
    import Select from '../select/Select.svelte';
    import ReportBreed from './Breed.svelte';
    import ReportBroods from './Broods.svelte';
    import ReportLay from './Lay.svelte';
    import ReportNotes from './Notes.svelte';
    import ReportParents from './Parents.svelte';
    import ReportShow from './Show.svelte';

    export let promise;
    export let legend = '';
    export let link='';

    let report = null;
    let layer = true;

    let disabled = false;
    const groups = ['I', 'II', 'III' ];

    function onEdit() {
        disabled = false;
    }

    function onSubmit() {
        disabled = true;
        console.log('Submit', report);
        if( report.id === 0 ) {
            api.report.post( report )
                .then( response => {
                    report.id = response.id;
                    report = report;
                    console.log('Success', response);
                });
        } else {
            api.report.put( report )
                .then( ( response ) => {
                    console.log('Success', response);
            });
        }
    }

    function handle( promise ) {
        if( promise ) promise.then( data => {
            console.log( 'Report', data );
            report = data.report;
        }).catch( error => {
            console.error( 'Error', error );
        });
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

    $: handle( promise );
    $: update( report );

    onMount( () => {

    })



</script>

<form class='flex flex-col' on:submit|preventDefault={onSubmit}>
    <h2>Zuchtbuch tt Meldung</h2>
    {#if report}
        <div class='flex flex-col my-2'>
            <h4>Stamm {report.id}</h4>
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