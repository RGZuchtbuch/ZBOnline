<script>
    import { onMount } from 'svelte';
    import api from '../../js/api.js';
    import Select from '../input/Select.svelte';
    import {router} from "tinro";
    import Button from "../input/Button.svelte";
    import { Chart, Colors, BarController, BarElement, CategoryScale, LinearScale, Title, Tooltip } from 'chart.js';
//    import Chart from 'chart.js/auto';

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
    let chart = null;

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

    function showChart() {
        const context = canvas.getContext( '2d' );
        let labels = years.map(row => row.year);
        let data = years.map(row => row[ type ]);
        let data2 = null;//years.map(row => row[ type ] * 0.25);

        if( chart ) {
           chart.data.labels = labels;
           chart.data.datasets = [ {label:type+' über Jahre', data:data } ];
           chart.update();
       } else {
           chart = new Chart( context, {
               type:'bar',
               data: {
                   labels:labels,
                   datasets: [ {
                       label:type+' über Jahre',
                       data:data,
                   },{
                       label:type+' über Jahre',
                       data:data2,
                   }]
               },
               options: {
                   indexAxis:'y',
                   plugins: {
                       title: {
                           display: true,
                           text: 'Tiiitle',
                       },
                   },
                   scales: {
                       x: {
                            stacked:true,
                       },
                       y: {
                           reverse:true,
                           stacked:true,
                       }
                   }
               }
           });
       }
    }

    Chart.register(
        Colors, BarController, BarElement, CategoryScale, LinearScale, Title, Tooltip
    );

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
        <div>
            <h3>Jahre</h3>
            {#if years}
                {#each years as year}
                    <div class='flex'>
                        <div class='w-64'>{year.year}</div>
                        <div>{year.pairs}</div>
                    </div>
                {/each}
            {/if}
        </div>

    <div>
        <div>Title</div>
        <div class='border border-gray-600 p-4'>
            {#if years}
                <canvas id='chart' width='256' height='512' bind:this={canvas} />
            {/if}
        </div>
    </div>
</div>


<style>

</style>