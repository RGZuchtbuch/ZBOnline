<script>
    import api from '../../js/api.js';
    import Select from '../input/Select.svelte';
    import {router} from "tinro";

    let year = new Date().getFullYear();
    let sectionId = 2;
    let type = 1;
    let maxPairs = 0;

    const years = [ 2023, 2022, 2021, 2020 ];
    const sections = [
        { id:2, name:'Geflügel' },
        { id:3, name:'Groß & Wassergeflügel' },
        { id:4, name:'Hühner' }, { id:11, name:'- Hühner (groß)' }, { id:12, name:'- Zwerghühner' }, { id:13, name:'- Wachteln' },
        { id:5, name:'Tauben' }
    ];
    let districts = []; // the results per district

    function getData( year, sectionId, type ) {
        // first count of reports
        api.map.count.get( year, sectionId ).then( response => {
            districts = response.districts;
            maxPairs = 0;
            for( let district of districts ) {
                if( district.pairs > maxPairs ) {
                    maxPairs = district.pairs;
                }
            }
            console.log( 'Districts', districts, maxPairs );
        });
    }

    function selectYear( year ) {
        router.location.query.set( 'year', year );
    }
    function selectSection( sectionId ) {
        router.location.query.set( 'section', sectionId );
    }

    function lon( value ) {
        return ( value-5.74405 ) * 100;
    }
    function lat( value ) {
        return ( 55.02780 - value ) * 100;
    }

    function rel( value ) {
        return 5 + value/maxPairs * 50;
    }


    $: getData( year, sectionId, type );
    $: selectYear( year );
    $: selectSection( sectionId );

</script>


<h2 class='text-center' >Karte der Zuchtbuch Leistungen</h2>
<div class='flex gap-x-2 justify-center'>
    <Select class='w-24' label='Jahr' bind:value={year}>
        {#each years as year}
            <option value={year} >{year}</option>
        {/each}
    </Select>

    <Select class='w-24' label='Sparte' bind:value={sectionId}>
        {#each sections as section}
            <option value={section.id} >{section.name}</option>
        {/each}
    </Select>

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

        <svg width=939 height=796 class='border border-gray-600'>
            <image href='./assets/de.svg' x=0  y=0 width=959 height=796 class=''/>
            {#each districts as district }
                <circle cx={lon(district.longitude)} cy={lat(district.lattitude)} r={rel(district.pairs)} stroke='gray' stroke-width='2' fill='#ee7' class=''><title>{district.name} hat {district.pairs} Stämme</title></circle>
                <circle cx={lon(district.longitude)} cy={lat(district.lattitude)} r={1} stroke='gray' stroke-width='0' fill='#000' class=''><title>{district.name} hat {district.pairs} Stämme</title></circle>
                <text x={lon(district.longitude)} y={lat(district.lattitude)-10}  text-anchor="middle" stroke='black' stroke-width='1' fill='black' > {district.name} </text>
            {/each}
        </svg>
    {/if}
</div>


<style>

</style>