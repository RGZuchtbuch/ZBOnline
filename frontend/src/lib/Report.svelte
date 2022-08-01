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
    import ReportParents from './report/Parents.svelte';
    import ReportLay from './report/Lay.svelte';
    import ReportBroods from './report/Broods.svelte';

    export let promise;
    export let legend = '';
    export let link='';

    let report = null;
    let disabled = false;

    const groups = ['I', 'II', 'III' ];

    onMount( () => {
        if( promise ) promise.then( data => {
            console.log( 'Report', data );
            report = data;
            report.parents = [ { id:1, sex:'1.0', ring:'D13 AZ 999', score:94.2 }, { id:2, sex:'0.1', ring:'D13 NY 10', score:94.1 } ];
            report.lay = { start:'01.01.20', end:null, eggs:null };
            report.broods = [];//[{ id:1, start:null, eggs:null, fertile:null, hatched:null },{ id:2, start:null, eggs:null, fertile:null, hatched:null }];
        }).catch( error => {
            console.error( 'Error', error );
        });
    })

    // let parents = [ { sex:'1.0', ring:'D13 AZ 999', score:94.2 }, { sex:'0.1', ring:'D13 AZ 999', score:94.2 } ];


</script>

<div class='flex flex-col '>
    <h2>Zuchtbuch Meldung</h2>
    {#if report}
        <div class='flex flex-col my-2'>
            <div>Stamm</div>
            <div class='flex flex-row gap-x-1'>
                <InputText class='w-32' label='Züchter' value='Eelco Jannink' readonly {disabled}/>
                <InputNumber class='w-16' label='Jahr' bind:value={report.year} min='1850' max='2030' {disabled}/>
                <InputText class='w-16' label='Name' bind:value={report.name} spellcheck=false {disabled} required/>
                <Select class='w-12' label='Grppe' options={groups} bind:value={report.group} placeholder='?' {disabled} required>
                    {#each groups as group}
                        <option value={group}>{group}</option>
                    {/each}
                </Select>
            </div>
        </div>


        <ReportBreed bind:report={report} {disabled}/>

        <ReportParents bind:report={report} {disabled}/>

        <ReportLay bind:report={report} {disabled}/>

        <ReportBroods bind:report={report} {disabled} />


        <div>
            Report {report.id} {report.parents.length}
        </div>
    {/if}
</div>

<style>

</style>