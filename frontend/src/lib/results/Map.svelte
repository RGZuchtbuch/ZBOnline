<script>
    import {meta, router} from "tinro";
    import api from '../../js/api.js';
    import { calcColor, pct } from '../../js/util.js';
    import Select from '../input/Select.svelte';
    import Button from "../input/Button.svelte";
    import TimeLine from './TimeLine.svelte';
    import GeoMap from './GeoMap.svelte';

    const types = { // what options to show
        1: {
            id: 1,
            label: 'Mitglieder',
            extract: (result) => [result.members],
            title: (result) => ` hat ${result.members} Mitglieder`
        },
        2: {
            id: 2,
            label: 'Züchter',
            extract: (result) => [result.breeders],
            title: (result) => ` meldete ${result.breeders} Züchter`
        },
        3: {
            id: 3,
            label: 'Stämme',
            extract: (result) => [result.pairs],
            title: (result) => ` meldete ${result.pairs} Stämme`
        },
        10: {
            id: 10,
            label: 'Legeleistung',
            extract: (result) => [result.layEggs],
            title: (result) => ` legten ⌀ ${result.layEggs.toFixed(0)} Eier im Jahr`
        },
        11: {
            id: 11,
            label: 'Brutleistung',
            extract: (result) => [result.broodHatched, result.broodFertile, result.broodEggs],
            title: (result) => ` von ${result.broodEggs} war ${pct(result.broodFertile, result.broodEggs, 0)} befruchtet und es schlüpften ${pct(result.broodHatched, result.broodEggs, 0)}`
        },
        12: {
            id: 12,
            label: 'Schauleistung',
            min: 89,
            max: 97,
            extract: (result) => [result.showScore],
            title: (result) => ` ${result.showCount} Tiere erhielten ⌀ ${result.showScore.toFixed(1)} Punkte`
        },
    }

    //set by query
    let typeId = 3;
    let year = new Date().getFullYear(); // this year
    let districtId = 1;
    let sectionId = 2;
    let breedId = null;
    let colorId = null;

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

    function onQuery( route ) {
        console.log( 'onQuery', route.query );
        typeId = Number( route.query.type ) || typeId;
        year = Number( route.query.year ) || year;
        districtId = Number( route.query.district ) || districtId;
        sectionId = Number( route.query.section ) || sectionId;
        breedId = Number( route.query.breed ) || breedId;
        colorId = Number( route.query.color ) || colorId;
    }

    function onType( typeId ) {
        router.location.query.set( 'type', typeId );
    }
    function onYear( year ) {
        router.location.query.set( 'year', year );
    }
    function onDistrict( districtId ) {
        router.location.query.set( 'district', districtId );
    }
    function onSection( sectionId ) {
        if( ! $router.query.breed ) { // not though query
            breeds = [];
            breedId = null;
            colors = [];
            colorId = null;
        }
        api.section.breeds.get( sectionId ).then( response => {
            breeds = response.breeds;
            if( breeds.length === 0 ) {
                breedId = null;
                colorId = null;
            }
        } );
        router.location.query.set( 'section', sectionId );
    }

    function onBreed( breedId ) {
        console.log('C', $router.query.color );
        if ( !$router.query.color) {
            colors = [];
            colorId = null;
        }
        if (breedId) {
            api.breed.colors.get( breedId ).then( response => {
                colors = response.colors;
                if( colors.length === 0 ) {
                    colorId = null;
                    colors = [];
                    router.location.query.delete( 'color' );
                }
            });
//            router.location.query.delete( 'color' );
            router.location.query.set( 'breed', breedId );
        } else {
            colorId = null;
            colors = [];
            router.location.query.delete( 'breed' );
            router.location.query.delete( 'color' );
        }
    }
    function onColor( colorId ) {
        if (colorId) {
//            router.location.query.delete( 'breed' );
            router.location.query.set( 'color', colorId );
        } else {
            router.location.query.delete( 'color' );
        }

    }

    const on = {
        click: ( district ) => {
            return (event) => {
                console.log(district.name, 'clicked')
            }
        }
    }

    $: onQuery( $router );
    $: onType( typeId );
    $: onYear( year );
    $: onDistrict( districtId );
    $: onSection( sectionId );
    $: onBreed( breedId );
    $: onColor( colorId );

    $: console.log( 'Router', $router );

</script>


<h2 class='text-center' >Das Zuchtbuch, Daten und leistungen</h2>
<div class='flex gap-x-2'>
    <div class='w-20 font-semibold' >Filter</div>:

    <div class='flex flex-wrap gap-x-2'>{sectionId}
        <Select class='w-48' label='Sparte ' bind:value={sectionId}>
            {#each sections as section}
                <option value={section.id} selected={section.id === sectionId}>{section.name}</option>
            {/each}
        </Select>

        <div class='flex flex-wrap gap-x-2'>
            <Select class='w-80' label={'Rasse'+breedId} bind:value={breedId}>
                <option value={null}>-</option>
                {#each breeds as breed}
                    <option value={breed.id} selected={breed.id === breedId}> {breed.name} </option>
                {/each}
            </Select>

            <Select class='w-72' label={'Farbe'+colorId} bind:value={colorId}>
                <option value={null}>-</option>
                {#each colors as color}
                    <option value={color.id} selected={color.id === colorId}>{color.name}</option>
                {/each}
            </Select>
        </div>
    </div>
</div>
<hr>

<div class='flex flex-wrap gap-x-2'>
    <div class='w-20 font-semibold' >Ergebnisse</div>:{typeId}
    <Select class='w-48' label='Was sehen' bind:value={typeId}>
        {#each Object.values( types ) as type, i }
            <option value={ type.id } > { type.label }</option>
        {/each}
    </Select>
</div>


<div class='bg-white border border-gray-600 rounded-b flex flex-col overflow-y-scroll scrollbar p-2'>
    <h3 class='text-center'>{types[typeId].label}</h3>
    <div class='flex flex-row flex-wrap justify-evenly'>
        {#if districts}
            <TimeLine bind:districtId={districtId} bind:year={year} {sectionId} {breedId} {colorId} type={types[typeId]} />
            <GeoMap bind:year={year} bind:districtId={districtId} {sectionId} {breedId} {colorId} type={types[typeId]} />
        {/if}
    </div>
</div>


<style>

</style>