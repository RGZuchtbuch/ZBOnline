<script>
    import { draw, fade } from 'svelte/transition';
    import { goto } from '$app/navigation';
    import { page } from '$app/state';
    import {calcColor, dec, gpsToPx, pct} from '$lib/js/tools.js';
    import BdrgSVG from './BdrgSVG.svelte';

    const MAXBUBBLE = 35;

    let { report, typeId } = $props();

    let canvas = null;
//    let districts = null; // districts with fields
    let max = $state( null ); // for max values per field in district
    let map = $state( null ); //$state( { labels:[], coords:[], datasets:[] } )// datasets
    let colors = $state( {} );

    const types = { // what options to show
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
            map: (result) => [result.layEggs === null ? null : 100*result.layEggs],
            title: (result) =>  result.layEggs
                ? ` Legeleistung ⌀ ${dec( 100*result.layEggs)}% Eier/Jahr`
                : ' keine Angaben',
            getMax: districts => Math.max( ...districts.map( district => 100*district.layEggs ) ),
            getMin: districts => 0,
        },
        20: {
            id: 20,
            label: 'Brutleistung Leger',
            map: (result) => [ result.broodLayerHatched === null ? null : 100*result.broodLayerHatched, result.broodLayerFertile === null ? null : 100*result.broodLayerFertile], // for map and chart
            title: (result) => result.broodLayerEggs > 0
                ? ` Eingelegt ${dec(result.broodLayerEggs)} Eier, ${dec(100 * result.broodLayerFertile)}% waren befruchtet und es schlüpften ${dec(100*result.broodLayerHatched)}%`
                : ' keine Angaben',
            getMax: districts => 100,
            getMin: districts => 0,
        },
        21: {
            id: 21,
            label: 'Brutleistung Tauben',
            map: (result) => [ result.broodPigeonResult === null ? null : result.broodPigeonResult ], // for map and chart
            title: (result) => result.broodPigeonBreeders ?
                ` Aus ${dec(result.broodPigeonHatched/result.broodPigeonResult)} Paare schlüpften ${dec(result.broodPigeonHatched)} Küken also ${dec(result.broodPigeonResult,1)} Küken / Paar`
                : ` keine Angaben`,
            getMax: districts => Math.max( ...districts.map( district => district.broodPigeonResult ) ),
            getMin: districts => 0,
        },

        30: {
            id: 30,
            label: 'Schauleistung',
            map: (result) => [ result.showScore === null ? null : result.showScore ],
            title: (result) => result.showCount
                ? ` ${result.showCount} Tiere erhielten ⌀ ${dec(result.showScore, 1)} Punkte`
                : ` keine Angaben`,
            tooltip: 'Bewertungen der Tiere (u), 90 (b) .. 97 (v) Punkte',
            getMax: districts => 97,
            getMin: districts => 89,
        },
    }
    //let type = $state( types[ typeId ] );


    $effect( () => {
        updateMap( report, typeId );
    });

    function calcMaxValues( districts ) {

        // TODO, following still needed ?
        if( districts ) {
            max = { breeders:null, pairs:null, lay:null, brood:null, show:null };
            max.breeders = Math.max( ...districts.map( district => district.breeders ) ); // max of array of all breeders
            max.pairs = Math.max( ...districts.map( district => district.pairs ) );
            max.lay = 365;
            max.brood = Math.max( ...districts.map( district => district.broodLayerEggs ) );
            max.show = 97;
        }
    }

    function updateMap( report, typeId ) {
        let type = types[ typeId ];
        const labels = [];
        const coords = []
        const datasets = [];
        const titles = [];
        let max = 1;
        if( report.districts ) {
            type.max = type.getMax(report.districts);
            type.min = type.getMin(report.districts);
            // fill label;s and datasets depending on rows
            for( let district of report.districts ) {
                labels.push(district.name);
//                coords.push( gpsToPx( 380, 512, 5.7, 15.0, 47.5, 55.0, district.longitude, district.latitude ) );
//                coords.push( gpsToPx( 360, 485, 5.7, 15.0, 47.5, 55.0, district.longitude, district.latitude ) );
                coords.push( gpsToPx( 448, 576, 5.7, 15.0, 47.5, 55.0, district.longitude, district.latitude ) );
                titles.push( type.title( district ) );
                let values = type.map( district );

                colors[ district.id ] = values[0] === null ? '#DDD4' : calcColor( type.min, type.max, values[0], 0.25, 0 );

                for (let i=0; i<values.length; i++) {
                    if (datasets.length < i + 1) {
                        datasets.push({ data: [] })
                    }
                    const dataset = datasets[i];
                    const value = values[ values.length-i-1 ]; // last first
                    dataset.data.push(value);
                    if( value > max ) max = value; // remember max, for ?
                }
            }
            map = {
                labels:labels,
                coords:coords,
                datasets:datasets,
                titles:titles,
                min:type.min ? type.min : 0, // take preset if set
                max:type.max ? type.max : max,
                colors:[ '#74abf0C0', '#cdf094C0', '#F9CA9BC0', '#F9ACBCC0' ], // for each circle
            }
        }
    }

    function onClick( district ) {
        return ( event ) => {
            const url =new URL( page.url ); // for query changes
            url.searchParams.set( 'district', district.id );
            goto( url.href );
        }
    }
</script>



    <div class='flex flex-col border rounded-t-none' in:fade>

        <div class='relative'>
            <BdrgSVG {colors}/>

            {#if map}
                {#key map}
                    <svg class='absolute top-0 bottom-0' width='448' height='576' in:fade={{duration:1000}}>

                        <g in:fade={{duration:1000}} >
                            {#each report.districts as district, index }
                                <circle cx={map.coords[index].x} cy={map.coords[index].y} r={1+MAXBUBBLE} stroke='none' fill='#ccf0'></circle>
                                {#each map.datasets as dataset, d }
                                    <circle cx={map.coords[index].x} cy={map.coords[index].y}
                                            r={Math.max( MAXBUBBLE*(dataset.data[index] - map.min)/(map.max - map.min), 0 ) }
                                            stroke='#7777' fill={map.colors[d]} >
                                    </circle>
                                {/each}
                            {/each}

                            {#each map.labels as label, index }
                                <text x={map.coords[index].x} y={map.coords[index].y-10}  text-anchor="middle" stroke='#666' stroke-width='0.5' fill='#666' > {label} </text>
                            {/each}

                            {#each report.districts as district, index }
                                <circle cx={map.coords[index].x} cy={map.coords[index].y} r={1+MAXBUBBLE} stroke='#7770' fill='#0000' on:click={onClick(district)}>
                                    <title>{map.labels[index]} : {map.titles[index]}</title>
                                </circle>
                            {/each}
                        </g>
                    </svg>
                {/key}
            {/if}
        </div>
    </div>

<style>
    canvas {
        opacity: 0.5;
    }
</style>