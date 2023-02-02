<script>
    import api from '../../js/api.js';
    import { calcColor, pct } from '../../js/util.js';
    import Select from '../input/Select.svelte';
    import {router} from "tinro";
    import Button from "../input/Button.svelte";
    import TimeLine from './TimeLine.svelte';
    import GeoMap from './GeoMap.svelte';

    export let query = [];

    //set by query
    let year = new Date().getFullYear(); // this year
    let districtId = 1;
    let sectionId = 2;
    let breedId = null;
    let colorId = null;

    const types = { // what options to show
        breeders: { label:'Züchter', sets:1, extract: ( result ) => [ result.breeders ] },
        pairs: { label:'Stämme', sets:1, extract: ( result ) => [ result.pairs ] },
        lay: { label:'Legeleistung', sets:1, extract: ( result ) => [ result.layEggs ] },
        brood: { label:'Brutleistung', sets:3, extract: ( result ) => [ result.broodHatched, result.broodFertile-result.broodHatched, result.broodEggs-result.broodFertile ] },
        show: { label:'Schauleistung', sets:2, extract: ( result ) => [ result.showScore ] },
    }
    let type = types.pairs; // selected

    let max = {}
// from
    const years = [ 2023, 2022, 2021, 2020, 2019 ];
    const sections = [
        { id:2, name:'Geflügel' },
        { id:3, name:'Groß & Wassergeflügel' },
        { id:4, name:'Hühner' }, { id:11, name:' → Hühner (groß)' }, { id:12, name:' → Zwerghühner' }, { id:13, name:' → Wachteln' },
        { id:5, name:'Tauben' },
        { id:6, name:'Ziergeflügel'}
    ];
    let breeds = [];
    let colors = [];

    let districts = []; // the results per district

    function onQuery( query ) {
        console.log( 'Update query', query )
        year = Number( query.jahr ) || new Date().getFullYear();
        sectionId = Number( query.sparte ) || 2;
        breedId = Number( query.rasse ) || null;
        colorId = Number( query.farbe ) || null;
        console.log( 'Updated', year, sectionId, breedId, colorId );
    }

    function onYear( year ) {
        router.location.query.set( 'jahr', year );
    }
    function onSection( sectionId ) {
        breeds = [];
        breedId = null;
        colors = [];
        colorId = null;
        api.section.breeds.get( sectionId ).then( response => {
            breeds = response.breeds;
        } );
        router.location.query.set( 'sparte', sectionId );
    }

    function onBreed( breedId ) {
        colors = [];
        colorId = null;
        if (breedId) {
            api.breed.colors.get( breedId ).then( response => {
                colors = response.colors;
            });
            router.location.query.set( 'rasse', breedId );
        } else {
            router.location.query.delete( 'rasse' );
        }
    }
    function onColor( colorId ) {
        if (colorId) {
            router.location.query.set( 'farbe', colorId );
        } else {
            router.location.query.delete( 'farbe' );
        }

    }




    const on = {
        click: ( district ) => {
            return (event) => {
                console.log(district.name, 'clicked')
            }
        }
    }

//    $: onQuery( query );
//    $: onYear( year );
    $: onSection( sectionId );
    $: onBreed( breedId );
    $: onColor( colorId );

</script>


<h2 class='text-center' >Karte der Zuchtbuchleistungen</h2>
<div class='flex gap-x-2 justify-center'>
    <Select class='w-24' label='Jahr' bind:value={year}>
        {#each years as option}
            <option value={option} >{option}</option>
        {/each}
    </Select>

    <Select class='w-48' label='Sparte' bind:value={sectionId}>
        {#each sections as section}
            <option value={section.id}>{section.name}</option>
        {/each}
    </Select>

    <Select class='w-48' label='Rasse' bind:value={breedId}>
        <option value={null}>-</option>
        {#each breeds as breed}
            <option value={breed.id} >{breed.name}</option>
        {/each}
    </Select>

    <Select class='w-48' label='Farbe' bind:value={colorId}>
        <option value={null}>-</option>
        {#each colors as color}
            <option value={color.id} >{color.name}</option>
        {/each}
    </Select>

    <Select class='w-48' label='Was sehen' bind:value={type}>
        {#each Object.keys( types ) as key }
            <option value={ types[ key ] } > { types[ key ].label }</option>
        {/each}
    </Select>

</div>
Type {type.label}
<div class='bg-gray-100 border border-gray-600 rounded-b flex flex-row overflow-y-scroll scrollbar justify-around px-2'>
    {#if districts}
        <TimeLine bind:districtId={districtId} bind:year={year} {sectionId} {breedId} {colorId} {type} /> =
        <GeoMap bind:year={year} bind:districtId={districtId} {sectionId} {breedId} {colorId} {type} />
    {/if}
</div>


<style>

</style>