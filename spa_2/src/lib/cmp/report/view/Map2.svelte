<script>
    import { draw, fade } from 'svelte/transition';
    import { goto } from '$app/navigation';
    import { page } from '$app/state';
    import {calcColor, dec, gpsToPx, pct} from '$lib/js/tools.js';
    import BdrgSVG from './BdrgSVG.svelte';

    const MAXBUBBLE = 35;

    //let { report, typeId } = $props();
    let { title, districts, unit, scale=null } = $props();

    let canvas = null;
    let config = $state( null );

    $effect( () => {
        config = update( page.url );
    });

    function update() {
        if( districts ) {
            const names = districts.map( district => district.name );
            const coords = districts.map( district => gpsToPx( 448, 576, 5.7, 15.0, 47.5, 55.0, district.longitude, district.latitude ) );
            const values = districts.map( district => district[ unit.id ] * unit.factor );
            const min = scale ? scale : 0;
            const max = scale && scale.max ? scale.max : values.reduce( ( result, value ) => Math.max( result, value ), 0 );
            const colors = [];
            for( const district of districts ) {
                const value = district[ unit.id ] * unit.factor;
                colors[ district.id ] = value === null || value === 0 ? '#DDD2' : calcColor( min, max, value, 0.25, 0);
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
                            {#each config.coords as coord, index } <!-- values -->
                                {#if config.values[ index ] }
                                    <circle cx={ coord.x } cy={ coord.y } r={ Math.min( MAXBUBBLE, Math.max( MAXBUBBLE * ( config.values[ index ] - config.scale.min ) / ( config.scale.max - config.scale.min ), 0) ) } stroke='#88A8' fill='#ACFF'></circle>
                                {/if}
                            {/each}
                            {#each config.coords as coord, index } <!-- labels -->
                                <text x={ coord.x } y={ coord.y-10 } text-anchor="middle" stroke='#666' stroke-width='0.25' fill='#666' > {config.names[ index ] } </text>
                            {/each}
                            {#each config.coords as coord, index } <!-- max circle, clickable -->
                                <circle cx={ coord.x } cy={ coord.y } r={MAXBUBBLE} stroke='#88F8' fill='#0000' role='button' onclick={ onClick( index ) }>
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