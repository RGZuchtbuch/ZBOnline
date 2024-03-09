<script>
    import {onMount} from 'svelte';
    import api from "../../../../js/api.js";
    import validator from '../../../../js/validator.js';
    import Select from './Select.svelte';

    export let value = { sectionId:null, breedId:null, colorId:null }; // take from pair or other
    let classname = '';
    export { classname as class }

    let sections = [ { id:3, name:'Groß und Wassergeflügel' }, { id:11, name:'Große Hühner' }, { id:12, name:'Zwerghühner und Wachteln' }, { id:5, name:'Tauben' }, { id:6, name:'Ziergeflügel' } ];
    let breeds = [];
    let colors = [];


    let sectionId = null; // to avoid reloading
    let breedId = null; // to avoid reloading
    let colorId = null; // to avoid reloading

    const validate = {
        sectionId:      (v) => validator(v).number().if( v > 0 ).isValid(),
        breedId:        (v) => validator(v).number().if( v > 0 ).isValid(),
        colorId:        (v) => validator(v).number().if( v > 0 ).orNullIf( sectionId === 5 ).isValid(),
    }


/* hardcoded for specific selection.
    function getSections() {
        if( ! sections ) {
            api.section.children.get(2).then(data => {
                sections = data.sections
            });
        }
    }

 */
    function getBreeds( v ) {
        if( ! sectionId || value.sectionId !== sectionId ) {
            sectionId = value.sectionId;
            if( value.sectionId > 0 ) { // any selected
                api.section.breeds.get(value.sectionId).then(data => {
                    breeds = data.breeds;
                });
            }
        }
    }
    function getColors( v ) {
        if( ! breedId || value.breedId !== breedId ) {
            breedId = value.breedId;
            if (value.breedId > 0) { // any selected
                api.breed.colors.get(value.breedId).then(data => {
                    colors = data.colors
                });
            }
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
    }

    function update( value ) {
        // getSections(); // only once
        getBreeds( value.sectionId ); // after getSection
        getColors( value.breedId ); // after getBreeds !
    }

    onMount(() => {
    })

    $: update( value ); // from extern

    // need to take care of up[date from the url vs change in the form. Therefore the extern and intern step
    // solution with color not nice, but works.
</script>



<fieldset class='flex flex-row px-2 gap-x-1'>
    {#if sections}
        <Select class='w-60' label='Sparte *' bind:value={value.sectionId} error='Pflichtfeld' on:change={updateSection} validator={validate.sectionId}>
            <option value={null}></option>
            {#each sections as section }
                <option value={section.id} selected={section.id === value.sectionId}>{section.name}</option>
            {/each}
        </Select>

        {#if breeds}

            <Select class='w-104' label={'Rasse *'} bind:value={value.breedId} error='Pflichtfeld' on:change={updateBreed} validator={validate.breedId}>
                <option value={null}></option>
                {#each breeds as breed }
                    <option value={breed.id} selected={breed.id === value.breedId}>{breed.name}</option>
                {/each}
            </Select>

            {#if colors}
                <Select class='w-56' label='Farbenschlag' bind:value={value.colorId} error='Pflichtfeld' validator={validate.colorId}>
                    <option value={null}></option>
                    {#each colors as color }
                        <option value={color.id} selected={color.id === value.colorId}>{color.name}</option>
                    {/each}
                </Select>
            {/if}
        {/if}
    {/if}
</fieldset>


<style></style>