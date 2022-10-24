<script>
    import { onMount } from 'svelte';
    import { active, meta, router, Route} from 'tinro';
    import { dec, perc } from '../../js/util.js';

    import api from '../../js/api.js';

    import InputNumber from '../input/Number.svelte';
    import InputText   from '../input/Text.svelte';
    import Select from '../select/Select.svelte';
    import BreedSelect from '../select/Breed.svelte';

    export let legend = '';
    export let link='';
    export let districtId = null;

    let district = null;
    let sections = [];
    let groups = [];
    let years = [ 2022, 2021, 2020, 2019, 2018, 2017 ];

    let sectionId = null;
    let year = null; //TODO should be null to start
    let group = null;
    let results = [];

    getDistrict();
    getSections();
    getGroups();

    $: getResults( districtId, sectionId, year, group );

/**  functions **/

    function getDistrict() {
        api.district.get( districtId ).then( response => { district = response.district } );
    }
    function getSections() {
        api.section.children.get(2).then( response => { sections = response.sections } );
    }
    function getGroups() {
        api.groups.get().then( response => { groups = response.groups } );
    }

    function getResults( districtId, sectionId, year, group ) {
        if( districtId && sectionId && year && group ) {
            console.log( 'Ready to get');
            api.result.selection.get( districtId, sectionId, year, group )
                .then( response => { results = response.results } );
        }
    }

    function open( result ) {
        console.log( "open", result.name );
        api.breed.colors.get( result.breedId ).then( response => { result.colors = response.colors} )
        //breed.open = ! breed.open;
        //district = district; // trigger
    }

    function onSubmit( event ) {
        console.log( 'Submit' );
    }

    console.log( 'ResultInputHeader here');

</script>

<div class='flex flex-col'>
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


    <form class='flex flex-col' on:submit|preventDefault={onSubmit}>
        <div class='flex flex-row gap-x-1 text-xs'>
            <div class='w-8'>Rasse</div>
            <div class='w-72'>Farbe</div>

            <div class='w-12'>Zuchten</div>

            <div class='w-4'></div>

            <div class='w-12'>Hennen</div>
            <div class='w-10'>Eier/J</div>
            <div class='w-14'>Eiggewicht</div>

            <div class='w-4'></div>

            <div class='w-12'>Eingelegt</div>
            <div class='w-14'>Befruchtet</div>
            <div class='w-14'>Geschlüpft</div>

            <div class='w-4'></div>

            <div class='w-10'>Tiere</div>
            <div class='w-14'>Bewertung</div>
        </div>
        {#if district }
            {#each results as result }
                <div class='flex flex-row gap-x-1 text-xs'>
                    <div class='w-72 cursor-pointer' on:click={open(result)} >{result.name}</div>
                    <div class='w-8'></div>
                    {#if result.open }
                        <div class='w-12'>Zuchten</div>

                        <div class='w-4'></div>

                        <div class='w-12'>Hennen</div>
                        <div class='w-10'>Eier/J</div>
                        <div class='w-14'>Eiggewicht</div>

                        <div class='w-4'></div>

                        <div class='w-12'>Eingelegt</div>
                        <div class='w-14'>Befruchtet</div>
                        <div class='w-14'>Geschlüpft</div>

                        <div class='w-4'></div>

                        <div class='w-10'>Tiere</div>
                        <div class='w-14'>Bewertung</div>
                    {/if}
                </div>
                {#if result.open }
                    {#each result.colors as color}
                        <div class='flex flex-row gap-x-1 text-xs'>
                            <div class='w-8'></div>
                            <div class='w-72'>{color.name}</div>

                            <input class='w-12' bind:value={color.result.breeders} >

                            <div class='w-4'></div>

                            <input class='w-12' bind:value={color.result.lay.dames} >
                            <input class='w-10' bind:value={color.result.lay.eggs} >
                            <input class='w-14' bind:value={color.result.lay.weight} >

                            <div class='w-4'></div>

                            <input class='w-12' bind:value={color.result.brood.eggs} >
                            <input class='w-14' bind:value={color.result.brood.fertile} >
                            <input class='w-14' bind:value={color.result.brood.hatched} >

                            <div class='w-4'></div>

                            <input class='w-10' bind:value={color.result.show.count} >
                            <input class='w-14' bind:value={color.result.show.score} >
                        </div>
                    {/each}
                {/if}
            {/each}
        {/if}
    </form>
</div>


<style>
    input {
        @apply h-6 border border-gray-400 rounded;
    }

</style>