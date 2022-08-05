<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    import api from '../js/api.js';

    import InputDate   from './input/Date.svelte';
    import InputNumber from './input/Number.svelte';
    import InputText   from './input/Text.svelte';
    import InputRing from './input/Ring.svelte';
    import Select from './select/Select.svelte';
    import ReportBreed from './report/Breed.svelte';
    import ReportBroods from './report/Broods.svelte';
    import ReportLay from './report/Lay.svelte';
    import ReportNotes from './report/Notes.svelte';
    import ReportParents from './report/Parents.svelte';
    import ReportShow from './report/Show.svelte';

    export let promise;
    export let legend = '';
    export let link='';

    let pair = null;
    let breed = null;
    let parents = null;
    let lay = null;
    let broods = null;
    let show = null;
    let notes = null;

    let disabled = false;

    const groups = ['I', 'II', 'III' ];

    onMount( () => {
        if( promise ) promise.then( data => {
            console.log( 'Report', data );
            pair = data;
            breed = { sectionId:pair.sectionId, breedId:pair.breedId, colorId:pair.colorId };
            parents = pair.parents;
            lay = pair.lay;
            broods = pair.broods;
            show = pair.show;
        }).catch( error => {
            console.error( 'Error', error );
        });
    })

    function onEdit() {
        disabled = false;
    }

    function onSubmit() {
        disabled = true;
        pair.sectionId = breed.sectionId;
        pair.breedId = breed.breedId;
        pair.colorId = breed.colorId;
        pair.parents = parents;
        pair.lay = lay;
        pair.broods = broods;
        pair.show = show;
        pair.notes = notes;
        console.log('Submit', pair);
        if( pair.id ) {
            api.report.put( pair )
                .then( ( result ) => {
                    console.log('Success');
            });
        } else {
            api.report.post( pair )
                .then( ( result ) => {
                    console.log('Failed');
                });
        }
    }
</script>

<form class='flex flex-col' on:submit|preventDefault={onSubmit}>
    <h2>Zuchtbuch Meldung</h2>
    {#if pair}
        <div class='flex flex-col my-2'>
            <div>Stamm</div>
            <div class='flex flex-row gap-x-1'>
                <InputText class='w-32' label='Züchter' value='Eelco Jannink' readonly {disabled}/>
                <InputNumber class='w-16' label='Jahr' bind:value={pair.year} min='1850' max='2030' {disabled}/>
                <InputText class='w-16' label='Name' bind:value={pair.name} spellcheck=false {disabled} required/>
                <Select class='w-12' label='Gruppe' options={groups} bind:value={pair.group} placeholder='?' {disabled} required>
                    {#each groups as group}
                        <option value={group}>{group}</option>
                    {/each}
                </Select>
            </div>
        </div>


        <ReportBreed bind:breed={breed} {disabled}/>

        <ReportParents bind:paired={pair.paired} bind:parents={parents} {disabled}/>

        {#if breed.sectionId !== 5}
            <ReportLay bind:lay={lay} parents={parents} {disabled}/>
        {/if}

        <ReportBroods sectionId={breed.sectionId} bind:broods={broods} {disabled} />


        <ReportShow bind:show={show} {disabled} />

        <ReportNotes bind:notes={notes} {disabled} />



        {#if disabled}
            <button type='button' class='rounded border bg-gray-500 text-center text-white cursor-pointer' on:click={onEdit}>Meldung beartbeiten</button>
        {:else}
            <button type='submit' class='rounded border bg-gray-500 text-center text-white cursor-pointer'>Meldung speichern</button>
        {/if}
    {/if}
</form>

<style>

</style>