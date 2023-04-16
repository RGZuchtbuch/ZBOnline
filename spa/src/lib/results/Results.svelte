<script>
    import {Route, router, meta} from 'tinro';
    import api from '../../js/api.js';
    import {dec} from '../../js/util.js';
    import { calcColor, pct } from '../../js/util.js';
    import Select from '../input/Select.svelte';
    import Button from "../input/Button.svelte";
    import DistrictsPie from './DistrictsPie.svelte';
    import SectionsPie from './SectionsPie.svelte';
    import DistrictsMap from './DistrictsMap.svelte';
    import TimeLine from './TimeLine.svelte';
    import ResultList from './ResultList.svelte';
    import ScrollDiv from '../common/ScrollDiv.svelte';

    const route = meta();
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
            forPie: (result) => [result.breeders],
            title: (result) => result.breeders ? ` meldete ${dec(result.breeders)} Zuchten` : ' hat keine Daten',
        },
        3: {
            id: 3,
            label: 'Stämme',
            extract: (result) => [result.pairs],
            forPie: (result) => [result.pairs],
            title: (result) => result.pairs ? ` meldete ${dec(result.pairs)} Stämme` : ' hat keine Daten',
        },
        10: {
            id: 10,
            label: 'Legeleistung',
            extract: (result) => [result.layEggs],
            forPie: (result) => [result.layEggs],
            title: (result) =>  result.layEggs ? ` legten ⌀ ${dec(result.layEggs)} Eier im Jahr` : ' hat keine Daten',
        },
        11: {
            id: 11,
            label: 'Brutleistung',
            extract: (result) => [result.broodHatched, result.broodFertile, result.broodEggs], // for map and chart
            forPie: (result) => [ 100 * result.broodHatched / result.broodEggs], // for pie
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
            forPie: (result) => [result.showScore],
            title: (result) => result.showCount ? ` ${result.showCount} Tiere erhielten ⌀ ${dec(result.showScore, 1)} Punkte` : ' hat keine Daten',
        },
    }

    //set by query
    let typeId = null;
    let year = null;
    let rootDistrict = null;
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

    let districts = null; // map id->district

    function onQuery( route ) {
        typeId = Number( route.query.type ) || 2;
        year = Number( route.query.year ) || new Date().getFullYear() -1; // last year;
        districtId = Number( route.query.district ) || 1;
        sectionId = Number( route.query.section ) || 2;
        breedId = Number( route.query.breed ) || null;
        colorId = Number( route.query.color ) || null;

        loadBreeds();
        loadColors();
    }

    function loadDistricts() {
        api.district.children.get( 1 ).then( response => { // bdrg (1) is fixed root
            rootDistrict = response.district;
            districts = {};
            districts[ rootDistrict.id ] = rootDistrict;
            for( const district of rootDistrict.children ) districts[ district.id ] = district;
        })
    }

    function loadSections() {
        sections = [];
        api.section.descendants.get( 2 ).then( response => {
            sections = prepareSections( sections, response.section, '' );
        });
    }

    function prepareSections( sections, section, prepend ) { // recursive
        sections.push( { id:section.id, name:prepend+section.name } );
        if( prepend === '' ) prepend = ' → ';
        if( section && section.children ) {
            for( const child of section.children ) {
                prepareSections( sections, child, ' · '+prepend );
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

    loadDistricts();
    loadSections();
    $: onQuery( $router );
    $: onYear( year );
    $: onDistrict( districtId );

</script>

<Route path='/*' let:meta>
    <ScrollDiv>
        <div class='flex-col w-256 m-2 bg-header border rounded px-4 items-center gap-2 no-print'>
            <div class='flex flex-row gap-x-2'>
                <div class='w-8 font-semibold' >Filter</div>:
                <Select class='w-52' label='Sparte' value={sectionId} on:change={onSection}>
                    {#each sections as section}
                        <option value={section.id} selected={section.id === sectionId}> {section.name} </option>
                    {/each}
                </Select>

                <Select class='w-64' label={'Rasse'} value={breedId} on:change={onBreed}>
                    <option value={null}> ? </option>
                    {#each breeds as breed}
                        <option value={breed.id} selected={breed.id === breedId}> {breed.name} </option>
                    {/each}
                </Select>

                <Select class='w-60' label={'Farbe'} value={colorId} on:change={onColor}>
                    <option value={null}> ? </option>
                    {#each colors as color}
                        <option value={color.id} selected={color.id === colorId}>{color.name}</option>
                    {/each}
                </Select>
            </div>

            <div class='flex flex-row gap-x-2'>
                <div class='w-8 font-semibold' >Was</div>:
                <Select class='w-52' label='Was sehen' value={typeId} on:change={onType}>
                    {#each Object.values( types ) as type, i }
                        <option value={ type.id } > { type.label }</option>
                    {/each}
                </Select>

                <Select class='w-64' bind:value={districtId} label={'Landesverband'}>
                    {#if rootDistrict }
                        <option value={rootDistrict.id} selected={rootDistrict.id === districtId}>{rootDistrict.name}</option>
                        {#each rootDistrict.children as district}
                            <option value={district.id}  selected={district.id === districtId}>{district.name}</option>
                        {/each}
                    {/if}
                </Select>

                <Select class='w-32' bind:value={year} label='Jahr'>
                    {#each years as option}
                        <option value={option}>{option}</option>
                    {/each}
                </Select>
            </div>
        </div>

        {#if districts && districtId && year && sectionId}
            <h2 class='text-center' >Das Zuchtbuch : {types[typeId].label} für {districts[ districtId ].name} in {year}</h2>


            <div class='flex flex-row border border-gray-600 gap-x-8 justify-evenly'>
                <SectionsPie {districtId} {year} type={types[typeId]}/>
                <DistrictsPie {districtId} {year} {sectionId} {breedId} {colorId} type={types[typeId]} />
            </div>
            <h3 class='text-center'>{types[typeId].label}</h3>
            <div class='flex flex-row flex-wrap justify-evenly'>
                    <TimeLine bind:year={year} {districtId} {sectionId} {breedId} {colorId} type={types[typeId]} />
                    <DistrictsMap bind:districtId={districtId} {year} {sectionId} {breedId} {colorId} type={types[typeId]} />
            </div>

            <div class='print-break h-4'></div>

            <div class='m-2 border rounded'>
                <ResultList districtId={districtId} year={year} />
            </div>
        {/if}
    </ScrollDiv>

</Route>


