<script>
    import { createEventDispatcher } from 'svelte';
    import { draw, fade } from 'svelte/transition';
    import api from "../../../js/api.js";
    import {calcColor, dec, gpsToPx, pct} from '../../../js/util.js';
    import BdrgSVG from './BdrgSVG.svelte';

    export let typeId;
    export let year;
    export let sectionId;
    export let breedId;
    export let colorId;

    export let districtId; //TODO for feedback, maybe should be event

    let canvas = null;
    let districts = null; // districts with fields
    let max = null; // for max values per field in district
    let map = { labels:[], coords:[], datasets:[] }// datasets
    let colors = {};

    let type = null;
    const resultTypes = { // what options to show
        2: {
            id: 2,
            label: 'Zuchten',
            map: (result) => [result.breeders],
            title: (result) => result.breeders
                ? ` ${dec(result.breeders)} gemeldete Zuchten`
                : ' keine Angaben',
            max: null,
            min: null,
            getMax: districts => Math.max( ...districts.map( district => district.breeders ) ),
            getMin: districts => 0,

        },
        10: {
            id: 10,
            label: 'Legeleistung',
            map: (result) => [100*result.layEggs],
            title: (result) =>  result.layEggs
                ? ` Legeleistung ⌀ ${dec( 100*result.layEggs)}% Eier/Jahr`
                : ' keine Angaben',
            getMax: districts => Math.max( ...districts.map( district => 100*district.layEggs ) ),
            getMin: districts => 0,
        },
        20: {
            id: 20,
            label: 'Brutleistung Leger',
            map: (result) => [ 100*result.broodLayerHatched, 100*result.broodLayerFertile], // for map and chart
            title: (result) => result.broodLayerEggs > 0
                    ? ` Eingelegt ${dec(result.broodLayerEggs)} Eier, ${dec(100 * result.broodLayerFertile)}% waren befruchtet und es schlüpften ${dec(100*result.broodLayerHatched)}%`
                    : ' keine Angaben',
            getMax: districts => 100,
            getMin: districts => 0,
        },
        21: {
            id: 21,
            label: 'Brutleistung Tauben',
            map: (result) => [ result.broodPigeonProduction ], // for map and chart
            title: (result) => result.broodPigeonBreeders ?
                ` Bei ${dec(result.nonLayerPairs)} Paare schlüpften ${dec(result.chicks)} Küken also ${dec(result.broodPigeonProduction,1)} Küken / Paar`
                : ` keine Angaben`,
            getMax: districts => Math.max( ...districts.map( district => district.broodPigeonProduction ) ),
            getMin: districts => 0,
        },

        30: {
            id: 30,
            label: 'Schauleistung',
            map: (result) => [result.showScore ? result.showScore : 89 ],
            title: (result) => result.showCount
                ? ` ${result.showCount} Tiere erhielten ⌀ ${dec(result.showScore, 1)} Punkte`
                : ` keine Angaben`,
            tooltip: 'Bewertungen der Tiere (u), 90 (b) .. 97 (v) Punkte',
            getMax: districts => 97,
            getMin: districts => 89,
        },
    }

    function lon( value ) {
        return ( value-5.74405 ) * 55;
    }
    function lat( value ) {
        return ( 55.02780 - value ) * 80;
    }

    function loadDistricts( year, sectionId, breedID, colorId ) {
        map = null;
        let promise;
        if( colorId ) {
            promise = api.map.color.get( year, colorId )
        } else if( breedId ) {
            promise = api.map.breed.get( year, breedId )
        } else if( sectionId ) {
            promise = api.map.section.get( year, sectionId )
        }
        if( promise ) {
            promise.then(response => {
                districts = response.districts;
                calcMaxValues(districts);
            });
        }
    }

    function calcMaxValues( districts ) {

        // TODO, following still needed ?
        if( districts ) {
            max = {};
            max.breeders = Math.max( ...districts.map( district => district.breeders ) ); // max of array of all breeders
            max.pairs = Math.max( ...districts.map( district => district.pairs ) );
            max.lay = 365;
            max.brood = Math.max( ...districts.map( district => district.broodEggs ) );
            max.show = 97;
        }
    }



    function showMap( districts, typeId ) {
        type = resultTypes[ typeId ];
        if( districts ) {
            type.max = type.getMax(districts);
            type.min = type.getMin(districts);
        }
        const labels = [];
        const coords = []
        const datasets = [];
        const titles = [];
        let max = 1;
        if( districts ) {
            // fill label;s and datasets depending on rows
            districts.forEach(district => {
                labels.push(district.name);
                coords.push( gpsToPx( 380, 512, 5.7, 15.0, 47.5, 55.0, district.longitude, district.latitude ) );
                titles.push( type.title( district ) );
                let values = type.map( district );

                colors[ district.id ] = calcColor( type.min, type.max, values[0], 0.25, 0 );

                for (let i=0; i<values.length; i++) {
                    if (datasets.length < i + 1) {
                        datasets.push({ data: [] })
                    }
                    const dataset = datasets[i];
                    const value = values[ values.length-i-1 ]; // last first
                    dataset.data.push(value);
                    if( value > max ) max = value; // remember max, for ?
                }
            })
            map = {
                labels:labels,
                coords:coords,
                datasets:datasets,
                titles:titles,
                min:type.min ? type.min : 0, // take preset if set
                max:type.max ? type.max : max,
                colors:[ '#74abf0C0', '#cdf094C0', '#F9CA9BC0', '#F9ACBCC0' ], // for each circle
            };
            //console.log( 'Map', map );
        }
    }

    function onClick( district ) {
        return ( event ) => {
            districtId = district.id;
        }
    }



    const dispatch = createEventDispatcher();

    $: loadDistricts( year, sectionId, breedId, colorId );
    $: showMap( districts, typeId );

</script>


<div class='flex flex-col'>
    <h3 class='text-center'>In den Landesverbänden, {year} </h3>

    <div class='relative'>
        <BdrgSVG {colors}/>

        <svg class='absolute top-0 bottom-0 border border-gray-600' width=380 height=512>

            {#if map}
                <g in:fade={{duration:1000}} >
                    {#each districts as district, index }
                        <circle cx={map.coords[index].x} cy={map.coords[index].y} r={1+MAXBUBBLE} stroke='none' fill='#ccf0'></circle>
                        {#each map.datasets as dataset, d }
                            <circle cx={map.coords[index].x} cy={map.coords[index].y}
                                    r={MAXBUBBLE*(dataset.data[index] - map.min)/(map.max - map.min)}
                                    stroke='#7777' fill={map.colors[d]} >
                            </circle>
                        {/each}
                    {/each}

                    {#each map.labels as label, index }
                        <text x={map.coords[index].x} y={map.coords[index].y-10}  text-anchor="middle" stroke='#666' stroke-width='0.5' fill='#666' > {label} </text>
                    {/each}

                    {#each districts as district, index }
                        <circle cx={map.coords[index].x} cy={map.coords[index].y} r={1+MAXBUBBLE} stroke='#7770' fill='#0000' on:click={onClick(district)}>
                            <title>{map.labels[index]} : {map.titles[index]}</title>
                        </circle>
                    {/each}
                </g>
            {/if}
        </svg>
    </div>
</div>

<style>
    canvas {
        opacity: 0.5;
    }
</style>