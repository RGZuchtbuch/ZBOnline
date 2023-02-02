<script>
    import api from "../../js/api.js";
    import {pct} from "../../js/util.js";
    import Select from '../input/Select.svelte';

    export let type;
    export let year;
    export let sectionId;
    export let breedId;
    export let colorId;

    export let districtId; //TODO for feedback, maybe should be event

    let districts = null; // districts with fields
    let max = null; // for max values per field in district

    function lon( value ) {
        return ( value-5.74405 ) * 55;
    }
    function lat( value ) {
        return ( 55.02780 - value ) * 80;
    }

    function loadDistricts( year, sectionId, breedID, colorId ) {
        console.log( 'GeoMap loads districts' )
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
                calcMaxValues(districts)
                setTitles(districts)
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

    $: loadDistricts( year, sectionId, breedId, colorId );


</script>


<div class='flex flex-col'>
    <h3 class='text-center'>Karte mit Leistungen pro Landesverband {type.label}</h3>
    <Select bind:value={year} label='Jahr'>
        {#if districts}
            {#each [ 2023, 2022, 2021, 2020, 2019 ] as option}
                <option value={option}>{option}</option>
            {/each}
        {/if}
    </Select>

    <svg width=500 height=600 class='border border-gray-600'>
        <image href='./assets/de.svg' x=0 y=0 width=500 height=600 class=''/>
        {#if districts}
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
        {/if}
    </svg>
</div>