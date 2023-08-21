<script>
    import {onMount} from 'svelte';
    import api from "../../../js/api.js";
    import Select from '../input/Select.svelte';

    export let value = { sectionId:null, breedId:null, colorId:null };

    export let disabled;
    export let invalid = false;

    let classname = '';
    export { classname as class }

    let sectionId;
    let breedId;
    let colorId;

    let sections = [];
    let breeds = [];
    let colors = [];

    function getSections() {
        api.section.children.get(2).then(data => {
            sections = data.sections
        });
    }
    function getBreeds() {
        api.section.breeds.get(sectionId).then(data => {
            breeds = data.breeds;
        });
    }
    function getColors(breedId) {
        api.breed.colors.get(breedId).then(data => {
            colors = data.colors
        });
    }

    function updateSection() {
//        console.log('SectionChange', sectionId);
        if (sectionId) {
            getBreeds();
            colors = [];
        } else {
            breeds = [];
            colors = [];
        }
        breedId = null;
        colorId = null;
        validate();
    }

    function updateBreed() {
//        console.log('BreedChange', breedId);
        if (breedId) {
            api.breed.colors.get(breedId).then(data => {
                colors = data.colors
            });
        } else {
            colors = [];
        }
        colorId = null;
        validate();
    }

    function updateColor() {
        validate();
    }

    function validate() {
//        console.log('SelectBreed val', sectionId, breedId, colorId );
        if (sectionId === 5) {
            invalid = !sectionId || !breedId;
        } else {
            invalid = !sectionId || !breedId || !colorId;
        }
        if( ! invalid ) {
            value.sectionId = sectionId;
            value.breedId = breedId;
            value.colorId = colorId
        }
    }

    function update() {
//        console.log( 'SelectBreed update', value );
        sectionId = value.sectionId;
        breedId = value.breedId;
        colorId = value.colorId;
        getSections(); // only once
        getBreeds( sectionId ); // after getSection
        getColors( breedId ); // after getBreeds !
        validate();
    }

    onMount(() => {
    })

    $: update(value); // from extern
//    $: updateSection( sectionId );
//    $: updateBreed( breedId );
//    $: updateColor( colorId );

    // need to take care of up[date from the url vs change in the form. Therefore the extern and intern step
    // solution with color not nice, but works.
</script>


<div class='flex flex-col'>
    <div class='flex flex-row gap-x-1'>

        <Select class='w-48' label='Sparte' bind:value={sectionId} error='Pflichtfeld' on:change={updateSection} {disabled} required>
            <option value={null}></option>
            {#each sections as section }
                <option value={section.id} selected={section.id === sectionId}>{section.name}</option>
            {/each}
        </Select>

        <Select class='w-64' label={'Rasse'+breedId} bind:value={breedId} error='Pflichtfeld' on:change={updateBreed} {disabled} required>
            <option value={null}></option>
            {#each breeds as breed }
                <option value={breed.id} selected={breed.id === breedId}>{breed.name}</option>
            {/each}
        </Select>

        {#if sectionId !== 5 }
            <Select class='w-48' label='Farbe' bind:value={colorId} error='Pflichtfeld' on:change={updateColor} {disabled} required>
                <option value={null}></option>
                {#each colors as color }
                    <option value={color.id} selected={color.id === colorId}>{color.name}</option>
                {/each}
            </Select>
        {:else}
            <Select class='w-48' label='Farbe' bind:value={colorId} error='Pflichtfeld' on:change={updateColor} {disabled}>
                <option value={null}></option>
                {#each colors as color }
                    <option value={color.id} selected={color.id === colorId}>{color.name}</option>
                {/each}
            </Select>
        {/if}
    </div>
</div>

<style></style>