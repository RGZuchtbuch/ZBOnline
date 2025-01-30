<script>
    import {onMount} from 'svelte';
    import {router} from 'tinro'
    import dic from '../../js/dictionairy.js';
    import { standard } from '../../js/store.js';
    import api from "../../js/api.js";
    import validator from '../../js/validator.js';
    import Select from '../common/form/input/Select.svelte';

//    export let formType;
    export let pair; // = { sectionId:null, breedId:null, colorId:null }; // take from pair or other

    let classname = '';
    export { classname as class }

//    let sections = formType === PIGEONS ?
//        [ { id:5, name:'Tauben' } ] :
//        [ { id:3, name:'Groß & Wassergeflügel' }, { id:11, name:'Große Hühner' }, { id:12, name:'Zwerghühner & Wachteln' }, { id:6, name:'Ziergeflügel' } ];
    let sections = [ { id:3, name:'Groß & Wassergeflügel' }, { id:11, name:'Große Hühner' }, { id:12, name:'Zwerghühner' }, { id:13, name:'Legewachteln' }, { id:5, name:'Tauben' }, { id:6, name:'Ziergeflügel' } ];

    let breeds = [];
    let colors = [];

    let section = null;
    let breed = null;
    let color = null;

    const validate = {
        section:      (v) => validator(v).if( v ).isValid(),
        breed:        (v) => validator(v).if( v ).isValid(),
        color:        (v) => validator(v).if( v ).orNullIf( pair.sectionId === PIGEONS ).isValid(),
    }

    function update( pair ) {
        if( pair ) {
            section = getSection( pair.sectionId, $standard );
            if( section ) {
                breeds = getSortedBreeds(section);
                breed = breeds.find(breed => breed.id === pair.breedId);
                if( breed ) {
                    colors = breed.colors;
                    color = colors.find(color => color.id === pair.colorId);
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

    function getSortedBreeds( section ) {
        const breeds = getBreeds( section, [] );
        breeds.sort((a, b) => a.name < b.name ? -1 : a.name > b.name ? 1 : 0)
        return breeds;
    }

    function getBreeds( section, breeds ) { // recursive, helper for getSorted above
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
        breeds = [];
        colors = [];
        pair.breedId = pair.colorId = null;
        if( pair.sectionId ) {
            section = getSection(pair.sectionId, $standard); // from standard as sections is incomplete
            breeds = getSortedBreeds(section);
//            breeds = getBreeds(section);
//            breeds.sort((a, b) => a.name < b.name ? -1 : a.name > b.name ? 1 : 0)
            pair.breedId = pair.coloId = null;
        }
    }

    function onBreedChange( event ) {
        colors = [];
        pair.colorId = null;
        if( pair.breedId ) {
            const breed = breeds.find(breed => breed.id === pair.breedId);
            colors = breed.colors;
            pair.coloId = null;
        }
    }

    onMount(() => {
    })

    $: update( pair )

</script>



<fieldset class='flex flex-row px-2 gap-x-1'>
    {#if $standard && sections && pair }

        <Select class='w-60' label='Sparte *' bind:value={pair.sectionId} title={dic.title.immutable} error='Pflichtfeld' validator={validate.section} on:change={onSectionChange} disabled={pair.id}>
            <option value={null}></option>
            {#each sections as section }
                <option value={section.id} selected={section.id === pair.sectionId}>{section.name}</option>
            {/each}
        </Select>

        {#if breeds}

            <Select class='w-104' label={'Rasse *'} bind:value={pair.breedId} error='Pflichtfeld' validator={validate.breed} on:change={onBreedChange}>
                <option value={null}></option>
                {#each breeds as breed }
                    <option value={breed.id} selected={breed.id === pair.breedId} >{breed.name}</option>
                {/each}
            </Select>

            {#if colors}
                <Select class='w-56' label='Farbenschlag' bind:value={pair.colorId} error='Pflichtfeld' validator={validate.color}>
                    <option value={null}></option>
                    {#each colors as color }
                        <option value={color.id} selected={color.id === pair.colorId}>{color.name}</option>
                    {/each}
                </Select>
            {/if}
        {/if}
    {/if}
</fieldset>


<style></style>