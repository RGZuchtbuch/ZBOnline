<script>
    import { createEventDispatcher } from 'svelte';
    import { draw, fade } from 'svelte/transition';
    import api from "../../js/api.js";
    import { calcColor, gpsToPx, pct} from '../../js/util.js';
    import BdrgSVG from './BdrgSVG.svelte';

    import Select from '../common/input/Select.svelte';
//    import DistrictsMap from "./DistrictsMap.svelte";

    export let type;
    export let year;
    export let sectionId;
    export let breedId;
    export let colorId;

    export let districtId; //TODO for feedback, maybe should be event

    let districts = null; // districts with fields
    let max = null; // for max values per field in district
    let map = { labels:[], coords:[], datasets:[] }// datasets


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
        if( districts ) {
            max = {};
            max.breeders = Math.max( ...districts.map( district => district.breeders ) ); // max of array of all breeders
            max.pairs = Math.max( ...districts.map( district => district.pairs ) );
            max.lay = 365;
            max.brood = Math.max( ...districts.map( district => district.broodEggs ) );
            max.show = 97;
        }
    }



    function showMap( districts ) {
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
                colors:[ '#F9CA9BC0', '#F9ACBCC0', '#94cbf0C0'], // for each circle
            };
        }
    }

    function onClick( district ) {
        return ( event ) => {
            districtId = district.id;
        }
    }

    const dispatch = createEventDispatcher();

    $: loadDistricts( year, sectionId, breedId, colorId );
    $: showMap( districts, type );

</script>


<div class='flex flex-col'>
    <h3 class='text-center'>In den Landesverbänden, {year} </h3>


    <svg width=380 height=512 class='border border-gray-600'>
        <BdrgSVG />
        {#if map}
            <g in:fade={{duration:1000}} >
                {#each districts as district, index }
                    <circle cx={map.coords[index].x} cy={map.coords[index].y} r={1+MAXBUBBLE} stroke='none' fill='#ccf4'></circle>
                    {#each map.datasets as dataset, d }
                        <circle cx={map.coords[index].x} cy={map.coords[index].y}
                                r={MAXBUBBLE*(dataset.data[index] - map.min)/(map.max - map.min)}
                                stroke='#7777' fill={map.colors[d]} >
                        </circle>
                    {/each}
                    <circle cx={map.coords[index].x} cy={map.coords[index].y} r={1+MAXBUBBLE} stroke='#7777' fill='#0000' on:click={onClick(district)}>
                        <title>{map.labels[index]} : {map.titles[index]}</title>
                    </circle>
                {/each}

                {#each map.labels as label, index }
                    <text x={map.coords[index].x} y={map.coords[index].y-10}  text-anchor="middle" stroke='#666' stroke-width='1' fill='#666' > {label} </text>
                {/each}
            </g>
        {/if}
    </svg>
</div>

