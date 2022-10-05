<script>
    import { onMount } from 'svelte';
    import { active, meta, router, Route} from 'tinro';
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
    let years = [ 2022, 2021, 2020, 2019, 2018, 2017 ];

    let sectionId = null;
    let year = null; //TODO should be null to start
    let group = null;

    getDistrict();
    getSections();
    getGroups();

    $: router.goto( '/obmann/verband/'+districtId+'/leistungen/sparte/'+sectionId+'/jahr/'+year+'/gruppe/'+group );

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
</div>


<style>
    input {
        @apply h-6 border border-gray-400 rounded;
    }

</style>