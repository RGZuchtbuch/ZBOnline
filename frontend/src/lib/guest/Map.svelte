<script>
    import api from '../../js/api.js';
    import Select from '../input/Select.svelte';
    import {router} from "tinro";
    import Button from "../input/Button.svelte";

    export let query = [];

    //set by query
    let year;
    let sectionId;
    let breedId;
    let colorId;

    let type = 'showCount';
    let max = {}


    const years = [ 2023, 2022, 2021, 2020 ];
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

    function onShow() {
        let promise;
        if( colorId ) {
            promise = api.map.color.get( year, colorId )
        } else if( breedId ) {
            promise = api.map.breed.get( year, breedId )
        } else if( sectionId ) {
            promise = api.map.section.get( year, sectionId )
        }
        promise.then( response => {
            districts = response.districts;
            max = {};
            for( let district of districts ) {
                for( let key in district ) {
                    if( ! max[ key ] || district[ key ] > max[ key ] ) {
                        max[ key ] = district[ key ];
                    }
                }
            }
        });
    }

    function lon( value ) {
        return ( value-5.74405 ) * 70;
    }
    function lat( value ) {
        return ( 55.02780 - value ) * 80;
    }

    function rel( value ) {
        return 5 + value/max[ type ] * 40;
    }

    $: onQuery( query );
    $: onYear( year );
    $: onSection( sectionId );
    $: onBreed( breedId );
    $: onColor( colorId );

</script>


<h2 class='text-center' >Karte der Zuchtbuchleistungen</h2>
<div class='flex gap-x-2 justify-center'>
    <Select class='w-24' label='Jahr' bind:value={year}>
        {#each years as year}
            <option value={year} >{year}</option>
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
        <option value={'pairs'} >Stämme</option>
        <option value={'breeders'} >Züchter</option>
        <option value={'layEggs'} >Legeleistung</option>
        <option value={'broodHatched'} >Küken</option>
        <option value={'showCount'} >Ausgestellten Tieren</option>
        <option value={'showScore'} >Bewertung</option>
    </Select>

    <Button label='' value='Zeigen' on:click={onShow} />

</div>

<div class='bg-gray-100 border border-gray-600 rounded-b flex flex-row overflow-y-scroll scrollbar justify-around px-2'>
    {#if districts}
        <div>
            <h3>Verbände</h3>
            {#each districts as district}
                <div class='flex'>
                    <div class='w-64'>{district.name}</div>
                    <div>{district.pairs}</div>
                </div>
            {/each}
        </div>

        <svg width=600 height=600 class='border border-gray-600'>
            <image href='./assets/de.svg' x=0 y=0 width=600 height=600 class=''/>
            {#each districts as district }
                <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={rel(district[ type ])} stroke='gray' stroke-width='2' fill='#ee7' class=''><title>{district.name} => {district[ type ].toFixed( 0 ) }</title></circle>
                <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={1} stroke='gray' stroke-width='0' fill='#000' class=''></circle>
            {/each}

            {#each districts as district }
                <text x={lon(district.longitude)} y={lat(district.latitude)-10}  text-anchor="middle" stroke='black' stroke-width='1' fill='black' > {district.name} </text>
            {/each}
        </svg>
    {/if}
</div>


<style>

</style>