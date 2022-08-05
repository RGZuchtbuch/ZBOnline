<script>
    import { onMount } from 'svelte';
    import api from "../../js/api.js";
    import Select from './Select.svelte';

    export let sectionId;
    export let breedId;
    export let colorId;

    export let disabled;

    let sections;
    let breeds;
    let colors;

    onMount( () => {
        getSections();
        getBreeds( sectionId );
        getColors( breedId );
    })

//    $: getBreeds( sectionId );
//    $: getColors( breedId );

    const on = {
        sectionId: () => {
            console.log( 'On Section' );
            getBreeds( sectionId );
            colors = [];
        },
        breedId: () => {
            console.log( 'On Breed' );
            getColors(breedId);
        },
        colorId: () => {
            console.log( 'On Color' );
        }
    }

    function getSections() {
        api.section.getChildren(2).then( data => { sections = data });
    }

    function getBreeds( sectionId ) {
        api.section.getBreeds( sectionId ).then( data => { breeds = data });
    }

    function getColors( breedId ) {
        api.breed.getColors( breedId ).then( data => { colors = data });
    }

</script>


<div class='flex flex-col my-2'>
    <div class='flex flex-row gap-x-1'>

        <Select class='w-48' label='Sparte' options={sections} bind:value={sectionId} {disabled} required on:change={on.sectionId}>
            {#if sections}
                {#each sections as section }
                    <option value={section.id} selected={sectionId === section.id}>{section.name}</option>
                {/each}
            {/if}
        </Select>
        <Select class='w-64' label='Rasse' bind:value={breedId} {disabled} required on:change={on.breedId}>
            {#if breeds}
                {#each breeds as breed }
                    <option value={breed.id} selected={breedId === breed.id}>{breed.name}</option>
                {/each}
            {/if}
        </Select>
        <Select class='w-48' label='Farbe' options={colors} bind:value={colorId} {disabled} required on:change={on.colorId}>
            {#if colors}
                {#each colors as color }
                    <option value={color.id} selected={colorId === color.id}>{color.name}</option>
                {/each}
            {/if}
        </Select>
    </div>
</div>

<style></style>