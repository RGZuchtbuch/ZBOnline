<script>
    import api from '../../js/api.js';
    import { calcColor, pct } from '../../js/util.js';
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
// what
    const types = {
        breeders: { label:'Züchter' },
        pairs: { label:'Stämme' },
        lay: { label:'Legeleistung' },
        brood: { label:'Brutleistung' },
        show: { label:'Schauleistung' }
    }
// from
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
            calcMaxValues( districts )
            setTitles( districts )
        });
    }

    function calcMaxValues( districts ) {
        if( districts ) {
            max = {};
            max.breeders = Math.max( ...districts.map( district => district.breeders ) ); // max of array of all breeders
            max.pairs = Math.max( ...districts.map( district => district.pairs ) );
            max.lay = 365;
            max.brood = Math.max( ...districts.map( district => district.broodEggs ) );
            max.show = Math.max( ...districts.map( district => district.showCount ) );
        }
        console.log( 'Max', max );
        console.log( 'Pairs', districts.map( district => district.breeders ) );
    }

    function setTitles( districts ) {
        if( districts ) {
            districts.forEach( district => {
                district.title = {};
                district.title.breeders = `${district.name} hat von ${district.breeders} Züchter daten verarbeitet`;
                district.title.pairs = `${district.name} hat ${district.pairs} Zuchten gemeldet`;
                district.title.lay = `${district.name} hat eine durchschnitt Legeleistng von ${district.layEggs} Eier im Jahr`;
                district.title.brood =
                    ! district.broodEggs
                        ? `${district.name} hat keine bebrütete Eier`
                        : !district.broodFertile
                            ? `${district.name} hat ${district.broodEggs} bebrütete Eier`
                            : !district.broodHatched
                                ? `${district.name} hat ${district.broodEggs} bebrütete Eier davon ${pct(district.broodFertile, 1, 0 )}% befruchtet aber keiner geschlüpft`
                                : `${district.name} hat ${district.broodEggs} bebrütete Eier davon ${pct(district.broodFertile, 1, 0 )} befruchtet und ${pct(district.broodHatched, 1, 0 )} geschlüpft`;
                district.title.show =
                    ! district.showCount
                        ? `${district.name} hat keine ausgestellten Tiere gemeldet.`
                        : `${district.name} hat ${district.showCount} ausgestellten Tiere mit ${district.showScore.toFixed(1)} Punkte.`
            })
        }
    }

    function lon( value ) {
        return ( value-5.74405 ) * 55;
    }
    function lat( value ) {
        return ( 55.02780 - value ) * 80;
    }

    function rel( value ) {
        return 5 + value/max[ type ] * 40;
    }

    const on = {
        click: ( district ) => {
            return (event) => {
                console.log(district.name, 'clicked')
            }
        }
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
        {#each Object.keys( types ) as key }
            <option value={ types[ key ] } > { types[ key ].label }</option>
        {/each}
    </Select>

    <Button label='' value='Zeigen' on:click={onShow} />

</div>

<div class='bg-gray-100 border border-gray-600 rounded-b flex flex-row overflow-y-scroll scrollbar justify-around px-2'>
    {#if districts}
        <div>
            <h3>Verbände</h3>
            {#each districts as district}
                <div class='flex'>
                    <div class='w-64'>{district.name}</div>:
                    <div>{district.breeders}</div>:
                    <div>{district.pairs}</div>:
                    <div>{district.layEggs}</div>:
                    <div>{district.broodEggs}</div>:
                    <div>{max.brood}</div> =
                    <div>{5+MAXBUBBLE*district.broodEggs/max.brood}</div>
                </div>
            {/each}
        </div>
        <svg width=500 height=600 class='border border-gray-600'>
            <image href='./assets/de.svg' x=0 y=0 width=500 height=600 class=''/>
            {#each districts as district }
                {#if type===types.breeders }
                    <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={5+MAXBUBBLE} stroke='gray' stroke-width='1' fill='#eee3' class=''>
                        <title>{district.name} : {district.breeders} Züchter</title>
                    </circle>
                    <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={5+MAXBUBBLE*district.breeders/max.breeders} stroke='gray' stroke-width='2' fill='#7e77' class=''>
                        <title>{district.name} : {district.breeders} Züchter</title>
                    </circle>
                {:else if type===types.pairs}
                    <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={5+MAXBUBBLE} stroke='gray' stroke-width='1' fill='#eee3' class=''>
                        <title>{district.name} : {district.pairs} Zuchten</title>
                    </circle>
                    <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={5+MAXBUBBLE*district.pairs/max.pairs} stroke='gray' stroke-width='1' fill='#7e77' class=''>
                        <title>{district.name} : {district.pairs} Zuchten</title>
                    </circle>
                {:else if type===types.lay}
                    <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={5+MAXBUBBLE} stroke='gray' stroke-width='1' fill='#eee3' class=''>
                        <title>{district.title.lay}</title>
                    </circle>
                    <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={5+MAXBUBBLE*district.layEggs/max.lay} stroke='gray' stroke-width='1' fill='#7e77' class=''>
                        <title>{district.title.lay}</title>
                    </circle>
                {:else if type===types.brood}
                    <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={5+MAXBUBBLE*district.broodEggs/max.brood} stroke='gray' stroke-width='1' fill='#eee3' class=''>
                        <title>{district.title.brood}</title>
                    </circle>
                    <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={5+MAXBUBBLE*district.broodEggs*district.broodFertile/max.brood} stroke='gray' stroke-width='1' fill='#ee07' class=''>
                        <title>{district.title.brood}</title>
                    </circle>
                    <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={5+MAXBUBBLE*district.broodEggs*district.broodHatched/max.brood} stroke='gray' stroke-width='1' fill='#7e77' class=''>
                        <title>{district.title.brood}</title>
                    </circle>
                {:else if type===types.show}
                    <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={5+MAXBUBBLE*(district.showScore-89)/(97-89)} stroke='gray' stroke-width='1' fill={calcColor( 89, 97, district.showScore, 0.5 )}>
                    </circle>
                    <circle cx={lon(district.longitude)} cy={lat(district.latitude)} r={5+MAXBUBBLE} stroke='gray' stroke-width='1' fill='#0000' on:click={on.click(district)}>
                        <title>{district.title.show}</title>
                    </circle>
                {/if}

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