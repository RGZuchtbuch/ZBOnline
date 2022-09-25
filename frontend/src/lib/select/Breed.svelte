<script>
    import { onMount } from 'svelte';
    import api from "../../js/api.js";
    import Select from './Select.svelte';

    export let sectionId;
    export let breedId;
    export let colorId;

    export let disabled;

    let sections = [];
    let breeds = [] ;
    let colors = [];

    onMount( () => {})

    getSections(); // only once
    $: getBreeds( sectionId );
    $: getColors( breedId ); // after getBreeds !

    function getSections() {
        api.section.getChildren(2).then( data => { sections = data });
    }
    function getBreeds( sectionId ) {
        console.log( 'SelectBreed, getBreeds', sectionId );
        if( sectionId ) {
            api.section.getBreeds(sectionId).then(data => {
                breeds = data;
            });
            colors = [];
        }
    }
    function getColors( breedId ) {
        console.log( 'SelectBreed, getColors', breedId );
        if( breedId ) api.breed.getColors( breedId ).then( data => { colors = data });
    }

</script>


<div class='flex flex-col'>
    <div class='flex flex-row gap-x-1'>
        <Select class='w-48' label='Sparte' bind:value={sectionId} {disabled} required>
            <option value={null} hidden></option>
            {#each sections as section }
                <option value={section.id} selected={section.id === sectionId}>{section.name}</option>
            {/each}
        </Select>
        <Select class='w-64' label='Rasse' bind:value={breedId} {disabled} required>
            <option value={null} hidden></option>
            {#each breeds as breed }
                <option value={breed.id} selected={breed.id === breedId}>{breed.name}</option>
            {/each}
        </Select>
        <Select class='w-48' label='Farbe' bind:value={colorId} {disabled} required>
            <option value={null} hidden></option>
            {#each colors as color }
                <option value={color.id} selected={color.id === colorId}>{color.name}</option>
            {/each}
        </Select>
    </div>
</div>

<style></style>