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

    // for treelike
    let current = { breedId:null };
    let header = null;

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
    function clear( result ) {
        if( result.sectionId === 5) {
            // pigeons
        } else {
            // rest
        }
    }
/*
    function isHeader( result ) {
        if( result.breedId !== current.breedId ) {
            current.breedId = result.breedId;
            if( result.open === undefined ) {
                console.log( 'Set ', result.breedName );
                result.open = false;
            }
            current.open = result.open;
            console.log( 'header ', current.breedId, result.breedId, current.open );
            return true;
        }
        result.open = current.open; // open for all following result for this breed
        return false;
    }
*/
    function isHeader( result ) {
        if( ! header || result.breedId !== header.breedId ) { // next breed
            header = result;
            header.valid = false;
            if( header.open === undefined ) {
                header.open = false;
            }
            return true;
        }
        result.open = header.open; // open for all following result for this breed
        return false;
    }

    function isOpen( result ) {
        return result.open;
    }

    function open( result ) {
        console.log( "open", result.breedName );
        result.open = ! result.open;
        results = results; // trigger
    }

    function isValid( result ) { // TODO
        result.valid = result.breeders || result.layDames || result.layEggs || result.layWeight || result.broodEggs || result.broodFertile || result.broodHatched || result.showCount || result.showScore;
        if( result.valid && !header.changed ) {
            header.changed = true;
            //results = results;
        }
        return result.valid;
    }

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

        {#if sectionId !== 5}
            <Select label="Gruppe" bind:value={group}>
                <option value={null}></option>
                {#each groups as group}
                    <option value={group}>{group}</option>
                {/each}
            </Select>
        {/if}
    </div>
    <div class=''>
        <div>Rassen und Leistungen</div>
        <div class='flex flex-row gap-x-1 text-xs'>
            <div class='w-8'>Rasse</div>
            <div class='w-72'>Farbe</div>

            <div class='w-12'>Zuchten</div>

            <div class='w-4'></div>

            <div class='w-12'>Hennen</div>
            <div class='w-10'>Eier/J</div>
            <div class='w-14'>Eiggewicht</div>

            <div class='w-4'></div>

            <div class='w-12'>Eigelegt</div>
            <div class='w-14'>Befruchtet</div>
            <div class='w-14'>Geschlüpft</div>

            <div class='w-4'></div>

            <div class='w-10'>Tiere</div>
            <div class='w-14'>Bewertung</div>
        </div>
        {#each results as result}
            {#if isHeader( result ) }
                <div class='flex flex-row gap-x-1 text-xs'>
                    <div class='w-72 cursor-pointer' on:click={open(result)} >{result.breedName} {result.valid?'*':'-'}</div>
                    <div class='w-8'></div>
                    {#if isOpen( result ) }
                        <div class='w-12'>Zuchten</div>

                        <div class='w-4'></div>

                        <div class='w-12'>Hennen</div>
                        <div class='w-10'>Eier/J</div>
                        <div class='w-14'>Eiggewicht</div>

                        <div class='w-4'></div>

                        <div class='w-12'>Eigelegt</div>
                        <div class='w-14'>Befruchtet</div>
                        <div class='w-14'>Geschlüpft</div>

                        <div class='w-4'></div>

                        <div class='w-10'>Tiere</div>
                        <div class='w-14'>Bewertung</div>
                    {/if}
                </div>
            {/if}
            {#if isOpen( result )}
                <div class='flex flex-row gap-x-1 text-xs'>
                    <div class='w-8'></div>
                    <div class='w-72'>{result.colorName}</div>

                    <input class='w-12' bind:value={result.breeders} >

                    <div class='w-4'></div>

                    <input class='w-12' bind:value={result.layDames} >
                    <input class='w-10' bind:value={result.layEggs} >
                    <input class='w-14' bind:value={result.layWeight} >

                    <div class='w-4'></div>

                    <input class='w-12' bind:value={result.broodEggs} >
                    <input class='w-14' bind:value={result.broodFertile} >
                    <input class='w-14' bind:value={result.broodHatched} >

                    <div class='w-4'></div>

                    <input class='w-10' bind:value={result.showCount} >
                    <input class='w-14' bind:value={result.showScore} >

                    {#if isValid( result )} OO {/if}

                    <div onclick={clear( result )}>X</div>
                </div>
            {/if}
        {/each}
    </div>
</form>


<style>
    input {
        @apply h-6 border border-gray-400 rounded;
    }

</style>