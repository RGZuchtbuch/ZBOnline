<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import InputDate   from './input/Date.svelte';
    import InputNumber from './input/Number.svelte';
    import InputSelect from './input/Select.svelte';
    import InputText   from './input/Text.svelte';
    import InputComposition from './input/Composition.svelte';

    export let promise;
    export let legend = '';
    export let link='';

    let report = null;

    let disabled = false;

    const groups = ['I', 'II', 'III' ];
    const sections = [{ id:1, name:'Groß u. Wassergeflügel' }, { id:2, name:'Hühner' }, { id:3, name:'Tauben' }, { id:4, name:'Ziergeflügel' }];
    let breeds = [{ id:1000, name:'Bielefelder Zwergkennhuhn' }, { id:1024, name:'Zwerg Welsumer' }];
    let colors = [{ id:1, name:'kennsperber' }, { id:2, name:'silber-kennsperber' }];

    let composition = { sires:1, dames:1 };

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
                <InputNumber class='w-16' label='Jahr' bind:value={report.year} min='1850' max='2030' {disabled}/>
                <InputText class='w-16' label='Name' bind:value={report.name} {disabled} required/>
                <InputSelect class='w-12' label='Grppe' options={groups} bind:value={report.group} placeholder='?' {disabled} required>
                    {#each groups as group}
                        <option value={group}>{group}</option>
                    {/each}
                </InputSelect>
            </div>
        </div>

        <div class='flex flex-col my-2'>
            <div>Rasse</div>
            <div class='flex flex-row gap-x-1'>
                <InputSelect class='w-48' label='Sparte' options={sections} bind:value={report.sectionId} {disabled} required>
                    {#each sections as section }
                        <option value={section.id}>{section.name}</option>
                    {/each}
                </InputSelect>
                <InputSelect class='w-48' label='Rasse' options={breeds} bind:value={report.breedId} {disabled} required>
                    {#each breeds as breed }
                        <option value={breed.id}>{breed.name}</option>
                    {/each}

                </InputSelect>
                <InputSelect class='w-48' label='Farbe' options={colors} bind:value={report.colorId} {disabled} required >
                    {#each colors as color }
                        <option value={color.id}>{color.name}</option>
                    {/each}
                </InputSelect>
            </div>
        </div>

        <div class='flex flex-col my-2'>
            <div>Abstammung</div>
            <div class='flex flex-row> gap-x-1'>
                <InputComposition class='w-12' label='Stamm' bind:sires={composition.sires} bind:dames={composition.dames} error='z.B. 1.2' {disabled} required/>
                <InputDate class='w-24' label='Anpaarung' bind:value={report.paired} {disabled}/>
            </div>

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