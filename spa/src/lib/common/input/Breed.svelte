<script>
    import {onMount} from 'svelte';
    import api from "../../../js/api.js";
    import Select from './Select.svelte';

    export let value = { sectionId:null, breedId:null, colorId:null };

    export let disabled;
    export let invalid = false;

    let classname = '';
    export { classname as class }

    //let sectionId;
    //let breedId;
    //let colorId;

    let sections = [];
    let breeds = [];
    let colors = [];

    function getSections() {
        api.section.children.get(2).then(data => {
            sections = data.sections
        });
    }
    function getBreeds() {
        api.section.breeds.get( value.sectionId ).then(data => {
            breeds = data.breeds;
        });
    }
    function getColors() {
        if( value.breedId > 0 ) {
            api.breed.colors.get(value.breedId).then(data => {
                colors = data.colors
            });
        }
    }

    function updateSection() {
        if (value.sectionId) {
            getBreeds();
            colors = [];
        } else {
            breeds = [];
            colors = [];
        }
        value.breedId = null;
        value.colorId = null;
        validate();
    }

    function updateBreed() {
        if ( value.breedId ) {
            api.breed.colors.get( value.breedId ).then(data => {
                colors = data.colors
            });
        } else {
            colors = [];
        }
        value.colorId = null;
        validate();
    }

    function updateColor() {
        validate();
    }

    function validate() {
        if ( value.sectionId === 5 ) {
            invalid = ! value.sectionId || ! value.breedId;
        } else {
            invalid = ! value.sectionId || ! value.breedId || ! value.colorId;
        }
    }

    function update() {
        getSections(); // only once
        getBreeds( value.sectionId ); // after getSection
        getColors( value.breedId ); // after getBreeds !
        validate();
    }

    function onInput() {
    }

    onMount(() => {
    })

    $: update(value); // from extern

    // need to take care of up[date from the url vs change in the form. Therefore the extern and intern step
    // solution with color not nice, but works.
</script>



<fieldset class='flex flex-row gap-x-1' on:input={onInput}>

    <Select class='w-48' label='Sparte' bind:value={value.sectionId} error='Pflichtfeld' on:change={updateSection} {disabled} required>
        <option value={null}></option>
        {#each sections as section }
            <option value={section.id} selected={section.id === value.sectionId}>{section.name}</option>
        {/each}
    </Select>

    <Select class='w-64' label={'Rasse'} bind:value={value.breedId} error='Pflichtfeld' on:change={updateBreed} {disabled} required>
        <option value={null}></option>
        {#each breeds as breed }
            <option value={breed.id} selected={breed.id === value.breedId}>{breed.name}</option>
        {/each}
    </Select>

    {#if value.sectionId !== 5 }
        <Select class='w-48' label='Farbe' bind:value={value.colorId} error='Pflichtfeld' on:change={updateColor} {disabled} required>
            <option value={null}></option>
            {#each colors as color }
                <option value={color.id} selected={color.id === value.colorId}>{color.name}</option>
            {/each}
        </Select>
    {:else}
        <Select class='w-48' label='Farbe' bind:value={value.colorId} error='Pflichtfeld' on:change={updateColor} {disabled}>
            <option value={null}></option>
            {#each colors as color }
                <option value={color.id} selected={color.id === value.colorId}>{color.name}</option>
            {/each}
        </Select>
    {/if}
</fieldset>


<style></style>