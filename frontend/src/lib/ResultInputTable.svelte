<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import { dec, perc } from '../js/util.js';

    import api from '../js/api.js';

    import InputNumber from './input/Number.svelte';
    import InputText   from './input/Text.svelte';
    import Select from './select/Select.svelte';
    import BreedSelect from './select/Breed.svelte';

    export let legend = '';
    export let link='';
    export let districtId = null;

    let district = null;
    let sections = [];
    let groups = [];
    let results = [];
    let years = [ 2022, 2021, 2020, 2019, 2018, 2017 ];

    let sectionId = null;
    let year = null; //TODO should be null to start
    let group = null;

    getDistrict();
    getGroups();
    getSections();

    $: getResults( sectionId, year, group )

    function getResults( sectionId, year, group ) {
        console.log( sectionId, year, group );
        if( sectionId && year && group ) api.district.results.full.get( districtId, sectionId, year, group )
            .then( response => {
                results = response;
            })
    }
    function getDistrict() {
        api.district.get( districtId )
            .then( response => {
                district = response;
            })
    }
    function getGroups() {
        api.groups.get()
            .then( response => {
                groups = response;
            })
    }
    function getSections() {
        api.section.children.get(2)
            .then( response => {
                sections = response;
            })
    }

    function onSubmit( event ) {}

    function edit() {}
    function remove() {}

</script>

<form class='flex flex-col' on:submit|preventDefault={onSubmit}>
    <h2>Zuchtbuch Meldungen Verband {district ? district.name : '...'}</h2>
    <div class='flex flex-row mx-2 gap-x-4'>
        <Select label="Sparte" bind:value={sectionId}>
            <option value={null}></option>
            {#each sections as section}
                <option value={section.id}>{section.name}</option>
            {/each}
        </Select>

        <Select label="Jahr" bind:value={year}>
            <option value={null}></option>
            {#each years as year}
                <option value={year}>{year}</option>
            {/each}
        </Select>

        <Select label="Gruppe" bind:value={group}>
            <option value={null}></option>
            {#each groups as group}
                <option value={group}>{group}</option>
            {/each}
        </Select>
    </div>
    <div class=''>
        <div>Rassen und Leistungen</div>
        <div class='flex flex-row gap-x-1 text-xs'>
            <div class='w-8'>Jahr</div>
            <div class='w-8'>Grp</div>
            <div class='w-48'>Rasse</div>
            <div class='w-32'>Farbe</div>
            <div class='w-12'>Zuchten</div>
            <div class='w-12'>Hennen</div>
            <div class='w-10'>Eier/J</div>
            <div class='w-14'>Eiggewicht</div>
            <div class='w-12'>Eigelegt</div>
            <div class='w-14'>Befruchtet</div>
            <div class='w-14'>Geschüpft</div>
            <div class='w-10'>Tiere</div>
            <div class='w-14'>Bewertung</div>
        </div>
        {#each results as result}
            <div class='flex flex-row gap-x-1 text-xs'>
                <div class='w-8'>{year}</div>
                <div class='w-8'>{group}</div>

                <div class='w-48'>{result.breedName}</div>
                <div class='w-32'>{result.colorName}</div>
                <div class='w-12'>1</div>

                <div class='w-12'>{dec(result.layDames)}</div>
                <div class='w-10'>{dec(result.layEggs)}</div>
                <div class='w-14'>{dec(result.layWeight)}</div>

                <div class='w-12'>{dec(result.broodEggs)}</div>
                <div class='w-14'>{perc( result.broodFertile, result.broodEggs )}</div>
                <div class='w-14'>{perc( result.broodHatched, result.broodEggs )}</div>

                <div class='w-10'>{dec(result.showCount)}</div>
                <div class='w-14'>{dec(result.showScore)}</div>
                <div onclick={edit( result )}>e</div>
                <div onclick={remove( result )}>d</div>
            </div>
        {/each}
    </div>
</form>


<style>

</style>