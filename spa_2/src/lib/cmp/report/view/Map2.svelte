<script>
    import { draw, fade } from 'svelte/transition';
    import { goto } from '$app/navigation';
    import { page } from '$app/state';
    import {calcColor, dec, gpsToPx, pct} from '$lib/js/tools.js';
    import BdrgSVG from './BdrgSVG.svelte';

    const MAXBUBBLE = 35;

    //let { report, typeId } = $props();
    let { title, districts, unit, scale=null, factor=1.0, color={fill:'#ADF', border:'#48A'}, width=1.0 } = $props();


    let canvas = null;
//    let districts = null; // districts with fields
//    let max = $state( null ); // for max values per field in district
//    let map = $state( null ); //$state( { labels:[], coords:[], datasets:[] } )// datasets
//    let colors = $state( {} );

    // const types = { // what options to show
    //     2: {
    //         id: 2,
    //         label: 'Zuchten',
    //         map: (result) => [result.breeders],
    //         title: (result) => result.breeders
    //             ? ` ${dec(result.breeders)} gemeldete Zuchten`
    //             : ' keine Angaben',
    //         max: null,
    //         min: null,
    //         getMax: districts => Math.max( ...districts.map( district => district.breeders ) ),
    //         getMin: districts => 0,
    //
    //     },
    //     10: {
    //         id: 10,
    //         label: 'Legeleistung',
    //         map: (result) => [result.layEggs === null ? null : 100*result.layEggs],
    //         title: (result) =>  result.layEggs
    //             ? ` Legeleistung ⌀ ${dec( 100*result.layEggs)}% Eier/Jahr`
    //             : ' keine Angaben',
    //         getMax: districts => Math.max( ...districts.map( district => 100*district.layEggs ) ),
    //         getMin: districts => 0,
    //     },
    //     20: {
    //         id: 20,
    //         label: 'Brutleistung Leger',
    //         map: (result) => [ result.broodLayerHatched === null ? null : 100*result.broodLayerHatched, result.broodLayerFertile === null ? null : 100*result.broodLayerFertile], // for map and chart
    //         title: (result) => result.broodLayerEggs > 0
    //             ? ` Eingelegt ${dec(result.broodLayerEggs)} Eier, ${dec(100 * result.broodLayerFertile)}% waren befruchtet und es schlüpften ${dec(100*result.broodLayerHatched)}%`
    //             : ' keine Angaben',
    //         getMax: districts => 100,
    //         getMin: districts => 0,
    //     },
    //     21: {
    //         id: 21,
    //         label: 'Brutleistung Tauben',
    //         map: (result) => [ result.broodPigeonResult === null ? null : result.broodPigeonResult ], // for map and chart
    //         title: (result) => result.broodPigeonBreeders ?
    //             ` Aus ${dec(result.broodPigeonHatched/result.broodPigeonResult)} Paare schlüpften ${dec(result.broodPigeonHatched)} Küken also ${dec(result.broodPigeonResult,1)} Küken / Paar`
    //             : ` keine Angaben`,
    //         getMax: districts => Math.max( ...districts.map( district => district.broodPigeonResult ) ),
    //         getMin: districts => 0,
    //     },
    //
    //     30: {
    //         id: 30,
    //         label: 'Schauleistung',
    //         map: (result) => [ result.showScore === null ? null : result.showScore ],
    //         title: (result) => result.showCount
    //             ? ` ${result.showCount} Tiere erhielten ⌀ ${dec(result.showScore, 1)} Punkte`
    //             : ` keine Angaben`,
    //         tooltip: 'Bewertungen der Tiere (u), 90 (b) .. 97 (v) Punkte',
    //         getMax: districts => 97,
    //         getMin: districts => 89,
    //     },
    // }
    //let type = $state( types[ typeId ] );

    let config = $state( null );
    // let configured = $state( false );
    // let names = $state( null );
    // let coords = $state( null );
    // let values = $state( null );
    //const colors = [ '#74abf0C0', '#cdf094C0', '#F9CA9BC0', '#F9ACBCC0' ]; // for lv colors
//    let districts = $state( [] );


    $effect( () => {
        config = update( page.url );
    });

    function update() {
        if( districts ) {
            const names = districts.map( district => district.name );
            const coords = districts.map( district => gpsToPx( 448, 576, 5.7, 15.0, 47.5, 55.0, district.longitude, district.latitude ) );
            const values = districts.map( district => district[ unit ] );
            const min = scale ? scale : 0;
            const max = scale && scale.max ? scale.max : values.reduce( ( result, value ) => Math.max( result, value ), 0 );
//            const colors = districts.map( district => district[ unit ] === null ? '#DDD4' : calcColor( min, max, district[ unit ], 0.25, 0) );
            const colors = [];
            for( const district of districts ) {
                const value = district[ unit ];
                // if( value === null ) {
                //     console.log( 'Dnull', district,name );
                //     colors[ district.id ] = '#000';
                // } else {
                //     colors[district.id] = calcColor(min, max, district[unit], 0.25, 0);
                // }
                colors[ district.id ] = value === null || value === 0 ? '#DDD2' : calcColor( min, max, district[ unit ], 0.25, 0);
            }
            //const colors = districts.map( district => '#DDD4' );
            return { names:names, coords:coords, values:values, scale:{ min:min, max:max }, colors:colors };
        }
        return null;
    }

    function onClick( index ) {
        return ( event ) => {
            config = null;
            console.log( 'Click' );
            const district = districts[ index ];
            const url =new URL( page.url ); // for query changes
            url.searchParams.set( 'district', district.id );
            goto( url.href );
        }
    }

    // $inspect( 'Districts', districts );
     $inspect( 'District', districts );
</script>



    <div class='m-4 flex flex-col border rounded-t-none' in:fade>

        <div class='relative'>

            {#if config}
                <BdrgSVG colors={config.colors}/>

                {#key districts}
                    <svg class='absolute top-0 bottom-0' width='448' height='576' in:fade={{duration:1000}}>
                        <g in:fade={{duration:500}} >
                            {#each config.coords as coord, index } <!-- max circle -->
                                {#if config.values[ index ] }
                                    <circle cx={ coord.x } cy={ coord.y } r={ Math.min( MAXBUBBLE, Math.max( MAXBUBBLE * ( config.values[ index ] - config.scale.min ) / ( config.scale.max - config.scale.min ), 0) ) } stroke='#88A8' fill='#CCF8'></circle>
                                {/if}
                            {/each}
                            {#each config.coords as coord, index } <!-- max circle -->
                                <text x={ coord.x } y={ coord.y-10 } text-anchor="middle" stroke='#666' stroke-width='0.25' fill='#666' > {config.names[ index ] } </text>
                            {/each}
                            {#each config.coords as coord, index } <!-- max circle -->
                                <circle cx={ coord.x } cy={ coord.y } r={1+MAXBUBBLE} stroke='#88F8' fill='#0000' role='button' onclick={ onClick( index ) }>
                                    <title>{ config.values[ index ] }</title>
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