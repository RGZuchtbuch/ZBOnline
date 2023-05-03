<script>
    import { onMount } from 'svelte';
    import api from "../../js/api.js";
    import Select from '../common/input/Select.svelte';

    export let sectionId;
    export let breedId;
    export let colorId;

    export let disabled;

    let sections = [];
    let breeds = [] ;
    let colors = [];

    onMount( () => {})

    getSections(); // only once
    getBreeds( sectionId ); // after getSection
    getColors( breedId ); // after getBreeds !

    function getSections() {
        api.section.children.get(2).then( data => { sections = data.sections });
    }
    function getBreeds( sectionId ) {
        api.section.breeds.get(sectionId).then(data => {
            breeds = data.breeds;
        });
    }
    function onSectionChange( event ) {
        console.log( 'SectionChange', sectionId );
        if( sectionId ) {
            api.section.breeds.get(sectionId).then(data => { breeds = data.breeds });
            colors = [];
        } else {
            breeds = [];
            colors = [];
        }
        breedId = null;
        colorId = null;
    }
    function getColors( breedId ) {
        api.breed.colors.get( breedId ).then( data => { colors = data.colors });
    }
    function onBreedChange( event ) {
        console.log( 'BreedChange', breedId );
        if( breedId ) {
            api.breed.colors.get( breedId ).then( data => { colors = data.colors });
        } else {
            colors = [];
        }
        colorId = null;
    }

</script>


<div class='flex flex-col'>
    <div class='flex flex-row gap-x-1'>
        <Select class='w-48' label='Sparte' bind:value={sectionId} on:change={onSectionChange} {disabled} required>
            <option value={null} ></option>
            {#each sections as section }
                <option value={section.id} selected={section.id === sectionId}>{section.name}</option>
            {/each}
        </Select>
        <Select class='w-64' label='Rasse' bind:value={breedId} on:change={onBreedChange} {disabled} required>
            <option value={null} ></option>
            {#each breeds as breed }
                <option value={breed.id} selected={breed.id === breedId}>{breed.name}</option>
            {/each}
        </Select>
        <Select class='w-48' label='Farbe' bind:value={colorId} {disabled} required={sectionId!==5}>
            <option value={null}></option>
            {#each colors as color }
                <option value={color.id} selected={color.id === colorId}>{color.name}</option>
            {/each}
        </Select>
    </div>
</div>

<style></style>