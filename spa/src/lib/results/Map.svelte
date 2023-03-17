<script>
    import {router} from "tinro";
    import api from '../../js/api.js';
    import {dec} from '../../js/util.js';
    import { calcColor, pct } from '../../js/util.js';
    import Select from '../input/Select.svelte';
    import Button from "../input/Button.svelte";
    import TimeLine from './TimeLine.svelte';
    import GeoMap from './GeoMap.svelte';
    import ResultList from './ResultList.svelte';

    const types = { // what options to show
/*
        1: {
            id: 1,
            label: 'Mitglieder',
            extract: (result) => [result.members],
            title: (result) => ` hat ${dec(result.members)} Mitglieder`
        },
 */
        2: {
            id: 2,
            label: 'Zuchten',
            extract: (result) => [result.breeders],
            title: (result) => result.breeders ? ` meldete ${dec(result.breeders)} Zuchten` : ' hat keine Daten',
        },
        3: {
            id: 3,
            label: 'Stämme',
            extract: (result) => [result.pairs],
            title: (result) => result.pairs ? ` meldete ${dec(result.pairs)} Stämme` : ' hat keine Daten',
        },
        10: {
            id: 10,
            label: 'Legeleistung',
            extract: (result) => [result.layEggs],
            title: (result) =>  result.layEggs ? ` legten ⌀ ${dec(result.layEggs)} Eier im Jahr` : ' hat keine Daten',
        },
        11: {
            id: 11,
            label: 'Brutleistung',
            extract: (result) => [result.broodHatched, result.broodFertile, result.broodEggs],
            title: (result) => result.broodEggs ?
                ` von ${dec(result.broodEggs)} war ${pct(result.broodFertile, result.broodEggs, 0)} befruchtet und es schlüpften ${pct(result.broodHatched, result.broodEggs, 0)}` :
                ' hat keine Daten',
        },
        12: {
            id: 12,
            label: 'Schauleistung',
            min: 89,
            max: 97,
            extract: (result) => [result.showScore],
            title: (result) => result.showCount ? ` ${result.showCount} Tiere erhielten ⌀ ${dec(result.showScore, 1)} Punkte` : ' hat keine Daten',
        },
    }

    //set by query
    let typeId = 3;
    let year = null;
    let districtId = null;
    let sectionId = null;
    let breedId = null;
    let colorId = null;

    let max = {}

    const years = [];
    for( let year=new Date().getFullYear(); year >= STARTYEAR; year--) years.push( year );

    let sections = []

    let breeds = [];
    let colors = [];

    let districts = []; // the results per district

    function onQuery( route ) {
        typeId = Number( route.query.type ) || 3;
        year = Number( route.query.year ) || new Date().getFullYear(); // this year;
        districtId = Number( route.query.district ) || 1;
        sectionId = Number( route.query.section ) || 2;
        breedId = Number( route.query.breed ) || null;
        colorId = Number( route.query.color ) || null;
        loadBreeds();
        loadColors();
    }

    function loadSections() {
        sections = [];
        api.section.descendants.get( 2 ).then( response => {
            const root = response.section;
            sections = prepareSections( sections, root, '' );
            console.log( sections );
        });
    }

    function prepareSections( sections, section, prepend ) {
        sections.push( { id:section.id, name:prepend+section.name } );
        if( prepend == '' ) prepend = ' → ';
        if( section && section.children ) {
            for( const child of section.children ) {
                prepareSections( sections, child, ' ·'+prepend );
            }
        }
        return sections;
    }

    function loadBreeds() {
        breeds = [];
        if( sectionId ) api.section.breeds.get( sectionId ).then( response => { breeds = response.breeds } );
    }

    function loadColors() {
        colors = [];
        if( breedId ) api.breed.colors.get( breedId ).then( response => { colors = response.colors });
    }

    function onType( event ) {
        router.location.query.set( 'type', event.target.value );
    }
    function onYear( year ) {
        router.location.query.set( 'year', year );
    }
    function onDistrict( id ) {
        router.location.query.set( 'district', id );
    }
    function onSection( event ) {
        router.location.query.set( 'section', event.target.value );
        router.location.query.delete( 'breed' );
        router.location.query.delete( 'color' );
    }
    function onBreed( event ) {
        colorId = null;
        colors = [];
        router.location.query.set( 'breed', event.target.value );
        router.location.query.delete( 'color' );
    }
    function onColor( event ) {
        router.location.query.set( 'color', event.target.value );
    }

    const on = {
        click: ( district ) => {
            return (event) => {
                console.log('clicked')
            }
        }
    }

    loadSections();
    $: onQuery( $router );
    $: onYear( year );
    $: onDistrict( districtId );
</script>


<h2 class='text-center' >Das Zuchtbuch, Daten und leistungen</h2>
<div class='flex gap-x-2'>
    <div class='w-20 font-semibold' >Filter</div>:

    <div class='flex flex-wrap gap-x-2'>
        <Select class='w-48' label='Sparte' value={sectionId} on:change={onSection}>
            {#each sections as section}
                <option value={section.id} >{section.name}</option>
            {/each}
        </Select>

        <div class='flex flex-wrap gap-x-2'>
            <Select class='w-80' label={'Rasse'} value={breedId} on:change={onBreed}>
                <option value={null}> ? </option>
                {#each breeds as breed}
                    <option value={breed.id} selected={breed.id === breedId}> {breed.name} </option>
                {/each}
            </Select>

            <Select class='w-72' label={'Farbe'} value={colorId} on:change={onColor}>
                <option value={null}> ? </option>
                {#each colors as color}
                    <option value={color.id} selected={color.id === colorId}>{color.name}</option>
                {/each}
            </Select>
        </div>
    </div>
</div>
<hr>

<div class='flex flex-wrap gap-x-2'>
    <div class='w-20 font-semibold' >Ergebnisse</div>:
    <Select class='w-48' label='Was sehen' value={typeId} on:change={onType}>
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

    <ResultList districtId={districtId} year={year} />

</div>



<style>

</style>