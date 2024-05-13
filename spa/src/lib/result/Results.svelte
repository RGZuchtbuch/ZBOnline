<script>
    import {Route, router, meta} from 'tinro';
    import { slide } from 'svelte/transition';
    import api from '../../js/api.js';

    import Form from '../common/form/Form.svelte';
    import Select from '../common/form/input/Select.svelte';
    import SectionsPie from './graphics/SectionsPie.svelte';
    import DistrictReport from './DistrictReport.svelte';
    import Help from './Help.svelte';

    import LayBar from './graphics/LayBar.svelte';
    import BroodBarLayers from './graphics/BroodBarLayers.svelte';
    import BroodBarPigeons from './graphics/BroodBarPigeons.svelte';
    import ShowBar from './graphics/ShowBar.svelte';

    import TimeLine from './graphics/Trend.svelte';
    import DistrictsMap from './graphics/Map.svelte';

    const route = meta();
    const types = [ {id:2, name:'Zuchten'}, {id:10, name:'Legeleistung'}, {id:20, name:'Brutleistung Leger'}, {id:21, name:'Brutleistung Tauben'}, {id:30, name:'Schauleistung'}];

    //set by query
    let typeId = 2;
    let type = types.find( item => item.id = typeId );
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
    let help = false;

    function onQuery( route ) {
        typeId = Number( route.query.type ) || 2;
        year = Number( route.query.year ) || new Date().getFullYear() -1; // last year;
        districtId = Number( route.query.district ) || 1;
        sectionId = Number( route.query.section ) || 2;
        breedId = Number( route.query.breed ) || null;
        colorId = Number( route.query.color ) || null;

        type = types.find( item => item.id = typeId );


        loadBreeds();
        loadColors();
    }

    function loadDistricts() {
        api.district.descendants.get( 1 ).then( response => { // bdrg (1) is fixed root
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
//        if( prepend === '' ) prepend = '';
        if( section && section.children ) {
            for( const child of section.children ) {
//                prepareSections( sections, child, '\xA0\xA0\xA0'+prepend );
                prepareSections( sections, child, '·  '+prepend ); // alt 255
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

    function onHelp() {
        help = ! help;
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




<div class='w-256 mt-8 flex bg-header rounded-t text-white no-print'>
    <h2 class='grow text-center text-2xl print'>Zuchtleistungen</h2>
    <div class='w-8 justify-center m-2 circled bg-alert text-white cursor-pointer no-print' on:click={onHelp} title='Anleitung'>?</div>
</div>

<div class='w-256 flex flex-col border border-gray-400 bg-gray-100 gab-2 no-print'>
    <Form>
    <div class='flex flex-row px-4 gap-x-2'>
        <div class='w-12 font-semibold text-white self-center' >Was :</div>
        <Select class='w-64' label='Was sehen' value={typeId} on:change={onType}>
            {#each types as item }
                <option value={ item.id }> { item.name }</option>
            {/each}
        </Select>

        <Select class='w-64' label={'Landesverband'} bind:value={districtId}>
            {#if rootDistrict }
                <option value={rootDistrict.id} selected={rootDistrict.id === districtId}>{rootDistrict.name}</option>
                {#each rootDistrict.children as district}
                    <option value={district.id}  selected={district.id === districtId}>{district.name}</option>
                {/each}
            {/if}
        </Select>

        <Select class='w-20' label='Jahr' bind:value={year}>
            {#each years as option}
                <option value={option}>{option}</option>
            {/each}
        </Select>
    </div>

    <div class='flex flex-row px-4 gap-x-2'>
        <div class='w-12 font-semibold text-white self-center' >Filter :</div>
        <Select class='w-64' label='Sparte' value={sectionId} on:change={onSection}>
            {#each sections as section}
                <option value={section.id} selected={section.id === sectionId}> {section.name} </option>
            {/each}
        </Select>

        <Select class='w-64 text-white' label={'Rasse'} value={breedId} on:change={onBreed}>
            <option value={null} title='Alle Rassen in der gewählten Sparte'> * </option>
            {#each breeds as breed}
                <option value={breed.id} selected={breed.id === breedId}> {breed.name} </option>
            {/each}
        </Select>

        <Select class='w-64' label={'Farbe'} value={colorId} on:change={onColor}>
            <option value={null} title='Alle farben der gewählten Rasse'> * </option>
            {#each colors as color}
                <option value={color.id} selected={color.id === colorId}>{color.name}</option>
            {/each}
        </Select>
    </div>
    </Form>
</div>

<div class='w-256 bg-white overflow-y-scroll border rounded-b border-gray-400 scrollbar print-no-border print-no-scrollbar'>

    {#if districts && districtId && year && sectionId}
        <div class='flex'>
            <h2 class='grow text-center' >Das Zuchtbuch : {districts[ districtId ].name} in {year}</h2>

            <div class='flex flex-col p-2 no-print'>
                <div class='text-sm'>eMail am Obmann</div>
                <a class='p-1 bg-alert rounded text-xl text-black text-center' href={'/kontakt/'+districtId}>&#9993;</a>
            </div>
        </div>

        <div class='flex flex-col my-2 border border-gray-400'>
            <h2 class='text-center'>Leistungen</h2>
            <div class='flex flex-row justify-around'>
                <div>
                    <LayBar {districtId} {year} {sectionId} {breedId} {colorId}></LayBar>
                </div>
                <div class='flex flex-row gap-x-4'>
                    <BroodBarLayers {districtId} {year} {sectionId} {breedId} {colorId}></BroodBarLayers>
                    <BroodBarPigeons {districtId} {year} {sectionId} {breedId} {colorId}></BroodBarPigeons>
                </div>
                <div>
                    <ShowBar {districtId} {year} {sectionId} {breedId} {colorId}></ShowBar>
                </div>
            </div>
        </div>
        {#if true}

        <div class='flex flex-row my-2 border border-gray-400 gap-x-8 justify-evenly'>
            <SectionsPie {districtId} {year} {typeId}/>
        </div>

        <div class='flex flex-col my-2 border border-gray-400'>
            <h2 class='text-center'>{type.name}</h2>
            <div class='flex flex-row flex-wrap justify-evenly'>
                    <TimeLine bind:year={year} {districtId} {sectionId} {breedId} {colorId} {typeId} />
                    <DistrictsMap bind:districtId={districtId} {year} {sectionId} {breedId} {colorId} {typeId} />
            </div>
        </div>
        {/if}
        {#if true}

        <div class='print-break'></div>
        {/if}
        <div class='border rounded print'>
            <DistrictReport districtId={districtId} year={year} />
        </div>
    {/if}
</div>

{#if help}
    <Help on:help={onHelp} />
{/if}

<style>
</style>




