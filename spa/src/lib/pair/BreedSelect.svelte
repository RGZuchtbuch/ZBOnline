<script>
    import {onMount} from 'svelte';
    import {router} from 'tinro'
    import { standard } from '../../js/store.js';
    import api from "../../js/api.js";
    import validator from '../../js/validator.js';
    import Select from '../common/form/input/Select.svelte';

    export let formType;
    export let value = { sectionId:null, breedId:null, colorId:null }; // take from pair or other

    let classname = '';
    export { classname as class }

    let sections = formType === PIGEONS ?
        [ { id:5, name:'Tauben' } ] :
        [ { id:3, name:'Groß & Wassergeflügel' }, { id:11, name:'Große Hühner' }, { id:12, name:'Zwerghühner & Wachteln' }, { id:6, name:'Ziergeflügel' } ];

    let breeds = [];
    let colors = [];

    let section = null;
    let breed = null;
    let color = null;

    const validate = {
        section:      (v) => validator(v).if( v ).isValid(),
        breed:        (v) => validator(v).if( v ).isValid(),
        color:        (v) => validator(v).if( v ).orNullIf( value.sectionId === PIGEONS ).isValid(),
    }

    function update( u, s ) {
        if( value && $standard ) {
            section = getSection( value.sectionId, $standard );
            if( section ) {
                breeds = getBreeds(section);
                breed = breeds.find(breed => breed.id === value.breedId);
                if( breed ) {
                    colors = breed.colors;
                    color = colors.find(color => color.id === value.colorId);
                }
            }
        } else {
            console.log( 'Update null' );
        }
    }

    function getSection( id, section ) { // recursive
        if( section ) {
            if( section.id === id ) return section;
            for (const child of section.children) {
                const childSection = getSection(id, child);
                if (childSection) return childSection;
            }
        }
        return null;
    }

    function getBreeds( section, breeds ) { // recursive
        if( breeds === undefined ) breeds = []; // init
        if( section ) {
            breeds.push( ...section.breeds );
            for( const childSection of section.children ) {
                getBreeds( childSection, breeds );
            }
        }
        return breeds;
    }

    function onSectionChange( event ) {
        section = getSection( value.sectionId, $standard ); // from standard as sections is incomplete
        breeds = getBreeds( section );
        colors = [];
        value.breedId = value.coloId = null;
    }

    function onBreedChange( event ) {
        const breed = breeds.find( breed => breed.id === value.breedId );
        colors = breed.colors;
        value.coloId = null;
    }

    onMount(() => {
    })

    $: update( $router.url, $standard )

</script>



<fieldset class='flex flex-row px-2 gap-x-1'>
    {#if $standard && sections && value }
        <Select class='w-60' label='Sparte *' bind:value={value.sectionId} error='Pflichtfeld' validator={validate.section} on:change={onSectionChange}>
            <option value={null}></option>
            {#each sections as section }
                <option value={section.id} selected={section.id === value.sectionId}>{section.name}</option>
            {/each}
        </Select>

        {#if breeds}

            <Select class='w-104' label={'Rasse *'} bind:value={value.breedId} error='Pflichtfeld' validator={validate.breed} on:change={onBreedChange}>
                <option value={null}></option>
                {#each breeds as breed }
                    <option value={breed.id} selected={breed.id === value.breedId} >{breed.name}</option>
                {/each}
            </Select>

            {#if colors}
                <Select class='w-56' label='Farbenschlag' bind:value={value.colorId} error='Pflichtfeld' validator={validate.color}>
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