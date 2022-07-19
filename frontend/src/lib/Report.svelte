<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import InputDate   from './input/Date.svelte';
    import InputNumber from './input/Number.svelte';
    import InputSelect from './input/Select.svelte';
    import InputText   from './input/Text.svelte';

    export let promise;
    export let legend = '';
    export let link='';

    let report = null;

    let disabled = false;

    const groups = [{ value:'I', name:'I' }, { value:'II', name:'II' }, { value:'III', name:'III' }];
    const sections = [{ value:1, name:'Groß u. Wassergeflügel' }, { value:2, name:'Hühner' }, { value:3, name:'Tauben' }, { value:4, name:'Ziergeflügel' }];
    let breeds = [];
    let colors = [];

    onMount( () => {
        if( promise ) promise.then( data => {
            console.log( 'Report', data );
            report = data;
        }).catch( error => {
            console.error( 'Error', error );
        });
    })


</script>

<div class='flex flex-col '>
    <h2>Zuchtbuch Meldung</h2>
    {#if report}
        <div class='flex flex-col my-2'>
            <div>Stamm</div>
            <div class='flex flex-row gap-x-1'>
                <InputText class='w-32' label='Züchter' value='Eelco Jannink' readonly {disabled}/>
                <InputNumber class='w-16' label='Jahr' bind:value={report.year} {disabled}/>
                <InputText class='w-16' label='Name' bind:value={report.name} {disabled}/>
                <InputSelect class='w-12' label='Gruppe' options={groups} bind:value={report.group} {disabled}/>
                <InputDate class='w24' label='Anpaarung' bind:value={report.paired} {disabled}/>
            </div>
        </div>

        <div class='flex flex-col my-2'>
            <div>Rasse</div>
            <div class='flex flex-row gap-x-1'>
                <InputSelect class='w-48' label='Sparte' options={sections} bind:value={report.section} {disabled}/>
                <InputSelect class='w-48' label='Rasse' options={breeds} bind:value={report.breed} {disabled}/>
                <InputSelect class='w-48' label='Farbe' options={colors} bind:value={report.color} {disabled}/>
            </div>
        </div>

        <div class='flex flex-col my-2'>
            <div>Abstammung</div>
            <div class='flex flex-row'>
                <div class='flex flex-col p-1'>
                    <div class='label'>Sex</div>
                    <div class='data'>1.0</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Ring</div>
                    <div class='data'>D21 NH 239</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Note</div>
                    <div class='data'>94</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Abstammung</div>
                    <div class='data'>2021 C1</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Leistungen (L,B,S)</div>
                    <div class='data'>160 49, 90% 80%, 94.1</div>
                </div>

            </div>
        </div>

        Report {report.id}
    {/if}
</div>

<style>

</style>