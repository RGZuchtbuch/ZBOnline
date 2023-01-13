<script>
    import { onMount } from 'svelte';
    import api from '../../js/api.js';
    import Select from '../input/Select.svelte';
    import {router} from "tinro";
    import Button from "../input/Button.svelte";
    import { Chart, Colors, BarController, BarElement } from 'chart.js';

    export let query = [];

    //set by query
    //let year;
    let districtId;
    let sectionId;
    let breedId;
    let colorId;

    let type = 'showCount';
    //let max = {}


    //const years = [ 2023, 2022, 2021, 2020 ];
    let rootDistrict = null;
    const sections = [
        { id:2, name:'Geflügel' },
        { id:3, name:'Groß & Wassergeflügel' },
        { id:4, name:'Hühner' }, { id:11, name:' → Hühner (groß)' }, { id:12, name:' → Zwerghühner' }, { id:13, name:' → Wachteln' },
        { id:5, name:'Tauben' },
        { id:6, name:'Ziergeflügel'}
    ];
    let breeds = [];
    let colors = [];

    let years = []; // the results per year

    let canvas = null;

    function getDistricts() {
        api.district.children.get( 1 ).then( response => {
            rootDistrict = response.rootDistrict;
            console.log( 'Districts', rootDistrict );
        });
    }

    function onQuery( query ) {
        console.log( 'Update query', query )
        districtId = Number( query.verband ) || 2;
        sectionId = Number( query.sparte ) || 2;
        breedId = Number( query.rasse ) || null;
        colorId = Number( query.farbe ) || null;
        console.log( 'Updated', districtId, sectionId, breedId, colorId );
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
            promise = api.trend.color.get( districtId, colorId )
        } else if( breedId ) {
            promise = api.trend.breed.get( districtId, breedId )
        } else if( sectionId ) {
            promise = api.trend.section.get( districtId, sectionId )
        }
        promise.then( response => {
            years = response.years;
            showChart();
        });
    }

    function lon( value ) {
        return ( value-5.74405 ) * 70;
    }
    function lat( value ) {
        return ( 55.02780 - value ) * 80;
    }

    function rel( value ) {
//        return 5 + value/max[ type ] * 40;
        return 100;
    }

    Chart.register(
        Colors, BarController, BarElement,
    );

    let chart = null;
    function showChart() {
       const context = canvas.getContext( '2d' );
       if( chart ) {
           chart.data.labels = years.map(row => row.year);
           chart.data.datasets = [ {label:type+' über Jahre', data:years.map(row => row[ type ]) } ];
           chart.update();
       } else {
           chart = new Chart( context, {
               type:'bar',
               data: {
                   labels: years.map(row => row.year),
                   datasets: [ { label:type+' über Jahre', data: years.map(row => row[ type ] ) }
                   ]
               }
           });
       }
    }

    $: onQuery( query );
    $: onSection( sectionId );
    $: onBreed( breedId );
    $: onColor( colorId );

    getDistricts( 1 );// load all LV as child of bdrg



</script>


<h2 class='text-center' >Verlauf Zuchtbuchleistungen</h2>
<div class='flex gap-x-2 justify-center'>

    <Select class='w-48' label='Verband' bind:value={districtId}>
        {#if rootDistrict}
            <option value={rootDistrict.id}>{rootDistrict.name}</option>
            {#if rootDistrict.children}
                {#each rootDistrict.children as district}
                    <option value={district.id}> &#10551; {district.name}</option>
                {/each}
            {/if}
        {/if}
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
        <option value={'showCount'} >Ausgestellten Tiere</option>
        <option value={'showScore'} >Bewertung</option>
    </Select>

    <Button label='' value='Zeigen' on:click={onShow} />

</div>

<div class='bg-gray-100 border border-gray-600 rounded-b flex flex-row overflow-y-scroll scrollbar justify-around px-2'>
    {#if years}
        <div>
            <h3>Jahre</h3>
            {#each years as year}
                <div class='flex'>
                    <div class='w-64'>{year.year}</div>
                    <div>{year.pairs}</div>
                </div>
            {/each}
        </div>

        <div class='w-228 h-128 border border-gray-600'>
            <canvas id='chart' class='w-full h-full' bind:this={canvas} />
        </div>
    {/if}
</div>


<style>

</style>