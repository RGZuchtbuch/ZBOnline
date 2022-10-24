<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import { dec, perc } from '../js/util.js';

    import api from '../js/api.js';

    import InputNumber from './input/Number.svelte';
    import InputText   from './input/Text.svelte';
    import Select from './select/Select.svelte';
    import BreedSelect from './select/Breed.svelte';


    export let promise;
    export let legend = '';
    export let link='';
    export let districtId = null;

    let breeders = [];
    let sections = [];
    let breeds = [];
    let colors = [];
    let groups = [];

    let year = 2022;
    let breeder = null;
    let district = null;
    let name = null;

    let sectionId = null;
    let breed = null;
    let breedId = null;
    let color = null;
    let colorId = null;
    let group = "I";

    let layDames = null;
    let layEggs = null;
    let layWeight = null;

    let broodEggs = null;
    let broodFertile = null;
    let broodHatched = null;

    let showCount = null;
    let showScore = null;

    let valid = false;
    let vSource = false;
    let vBreed = false;
    let vLay = false;
    let vBrood = false;
    let vShow = false;

    let results = [];


    $: getBreed( breedId );
    $: getColor( colorId );

    $: vSource = validSource( year, breeder, districtId, name );
    $: vBreed = validBreed( sectionId, breedId, colorId, group );
    $: vLay = validLay( layDames, layEggs, layWeight );
    $: vBrood = validBrood( broodEggs, broodFertile, broodHatched );
    $: vShow = validShow( showCount, showScore );
    $: valid = vSource && vBreed && vLay && vBrood && vShow;

    function getBreed( id ) {
        console.log( "Getting breed ", breedId );
        if( id ) api.breed.get( id ).then( (response) => { breed = response; console.log( "Got ", breed.name );
        });
    }
    function getColor( id ) {
        if( id ) api.color.get( id ).then( (response) => { color = response });
    }


    onMount( () => {
        api.district.get( districtId ).then( (response) => { district = response });
        //api.district.getBreeders( districtId ).then( ( respoonse ) => { breeders = respoonse });
        api.group.getGroups().then( ( response ) => { groups = response });

    })

    function validSource( year, breeder, districtId, name ) {
//        let v = year > MINYEAR && year < MAXYEAR && breeder !== null && breeder.id > 0 && districtId > 0 && name != null && name !== '';
        let v = year > MINYEAR && year < MAXYEAR && districtId > 0;
        console.log( 'Validate source ', v );
        return v;
    }
    function validBreed( sectionId, breedId, colorId, group ) {
        let v = false;
        if( sectionId === 5 ) { // pidgeons
            v = sectionId > 0 && breedId > 0 && group != null;
        } else {
            v = sectionId > 0 && breedId > 0 && colorId > 0 && group != null;
        }
        console.log( 'Validate breed ', v );
        return v;

    }
    function validLay( layDames, layEggs, layWeight ) {
        let v = false;
        if( sectionId !== 5 ) {
            v = (layDames === null || layDames < MAXLAYDAMES) && layEggs <= MAXLAYEGGS && (layWeight === null || layWeight <= MAXLAYWEIGHT);
        } else {
            v = true;
        }
        console.log( 'Validate lay', v );
        return v;
    }
    function validBrood( broodEggs, broodFertile, broodHatched ) {
        let v = ( broodEggs === null && broodFertile === null && broodHatched === null ) || ( broodEggs>=0 && broodFertile <= broodEggs && broodHatched <= broodFertile );
        console.log( 'Validate brood', v );
        return v;
    }
    function validShow( showCount, showScore ) {
        let v = ( showCount === null && showScore === null ) || ( showCount>0 && showCount<MAXSHOWCOUNT && showScore>=MINSHOWSCORE && showScore<=MAXSHOWSCORE);
        console.log( 'Validate show', v );
        return v;
    }

    function onSubmit() {
        // validate and send trough api
        console.log( 'Store template' )
        const result = {
            districtId: districtId, year: year, group: group, breederId: null, name: null,
            sectionId: sectionId, breedId: breedId, breedName: breed.name, colorId: colorId?colorId:0, colorName: color?color.name:null, // names for view
            layDames: layDames, layEggs: layEggs, layWeight: layWeight,
            broodEggs: broodEggs, broodFertile: broodFertile, broodHatched: broodHatched,
            showCount: showCount, showScore: showScore
        }

        api.result.post( result )
            .then( id => {
                result.id = id;
                results = [ result, ...results ];
            })
            .catch( ( error ) => console.error( error ) );

        // prep for next entry
        name = null;
        if( sectionId === 5 ) breedId = null;
        colorId = null;
        layDames = layEggs = layWeight = null;
        broodEggs = broodFertile = broodHatched = null;
        showCount = showScore = null;
    }

    function edit() {
        
    }
    function remove() {}
</script>

<form class='flex flex-col' on:submit|preventDefault={onSubmit}>
    <h2>Zuchtbuch Meldungen Verband {district ? district.name : '...'}</h2>
    <div class='flex flex-col my-2'>
        <div>Züchter</div>
        <div class='flex flex-row gap-x-1'>
            <InputNumber class='w-16' label='Jahr' bind:value={year} min={MINYEAR} max={MAXYEAR} required/>

            <Select class='w-12' label='Gruppe' options={groups} bind:value={group} placeholder='?' required>
                {#each groups as group}
                    <option value={group}>{group}</option>
                {/each}
            </Select>
        </div>
    </div>


    <div class='flex flex-col my-2'>
        <div>Rasse</div>
        <div class='flex flex-row gap-x-1'>
            <BreedSelect bind:sectionId={sectionId} bind:breedId={breedId} bind:colorId={colorId}/>

        </div>
        {sectionId} - {breedId} - {colorId} : {group}
    </div>

    <div class='flex flex-row gap-x-14'>
        {#if sectionId && sectionId===5}
        {:else}
            <div class='flex flex-col my-2'>
                <div>Legeleistung (nur hühner)</div>
                <div class='flex flex-row gap-x-1'>
                    <InputNumber class='w-16' label='Hennen' bind:value={layDames} min=0 max={MAXLAYDAMES} />
                    <InputNumber class='w-16' label='Eier / Jahr' bind:value={layEggs} min=0 max={MAXLAYEGGS} />
                    <InputNumber class='w-16' label='Eiergewicht' bind:value={layWeight} min={MINLAYWEIGHT} max={MAXLAYWEIGHT} />
                </div>
            </div>
        {/if}

        <div class='flex flex-col my-2'>
            <div>Brutleistung (2x)</div>
            <div class='flex flex-row gap-x-1'>
                <InputNumber class='w-16' label='Eingelegt' bind:value={broodEggs} min=0 max={MAXBROODEGGS} />
                <InputNumber class='w-16' label='Befruchtet' bind:value={broodFertile} min=0 max={broodEggs} error={0+' - '+broodEggs} />
                <InputNumber class='w-16' label='Geschlüpft' bind:value={broodHatched} min=0 max={broodFertile} error={0+' - '+broodFertile} />
            </div>
        </div>

        <div class='flex flex-col my-2'>
            <div>Schauleistung</div>
            <div class='flex flex-row gap-x-1'>
                <InputNumber class='w-16' label='Tiere' bind:value={showCount} min=0 max={MAXSHOWCOUNT} />
                <InputNumber class='w-16' label='Bewertung' bind:value={showScore} min={MINSHOWSCORE} max={MAXSHOWSCORE} />
            </div>
        </div>
    </div>

    {#if valid}
        <button type='button' class='rounded border bg-gray-500 text-center text-white cursor-pointer' on:click={onSubmit}>Meldung speichern|</button>
    {:else}
        <div>{valid} = {vSource}, {vBreed}, {vLay}, {vBrood}, {vShow}</div>
    {/if}
</form>
<div class='border border-gray-400'></div>
<div class='flex flex-col my-2'>
    <div>Eingegeben</div>
    <div class='flex flex-row gap-x-1 text-xs'>
        <div class='w-8'>Jahr</div>
        <div class='w-8'>Grp</div>
        <div class='w-48'>Rasse</div>
        <div class='w-32'>Farbe</div>
        <div class='w-12'>Zuchten</div>
        <div class='w-12'>Hennen</div>
        <div class='w-10'>Eier/J</div>
        <div class='w-14'>Eiggewicht</div>
        <div class='w-12'>Eigelegt</div>
        <div class='w-14'>Befruchtet</div>
        <div class='w-14'>Geschüpft</div>
        <div class='w-10'>Tiere</div>
        <div class='w-14'>Bewertung</div>
    </div>
    {#each results as result}
        <div class='flex flex-row gap-x-1 text-xs'>
            <div class='w-8'>{result.year}</div>
            <div class='w-8'>{result.group}</div>

            <div class='w-48'>{result.breedName}</div>
            <div class='w-32'>{result.colorName}</div>
            <div class='w-12'>1</div>

            <div class='w-12'>{dec(result.layDames)}</div>
            <div class='w-10'>{dec(result.layEggs)}</div>
            <div class='w-14'>{dec(result.layWeight)}</div>

            <div class='w-12'>{dec(result.broodEggs)}</div>
            <div class='w-14'>{perc( result.broodFertile, result.broodEggs )}</div>
            <div class='w-14'>{perc( result.broodHatched, result.broodEggs )}</div>

            <div class='w-10'>{dec(result.showCount)}</div>
            <div class='w-14'>{dec(result.showScore)}</div>
            <div onclick={edit( result )}>e</div>
            <div onclick={remove( result )}>d</div>
        </div>
    {/each}
</div>


<style>

</style>