<script>
    import {router} from "tinro";
    import api from '../../../js/api.js';
    import Button from '../../common/input/Button.svelte';
    import InputNumber from '../../common/input/Number.svelte';
    import Select from '../../common/input/Select.svelte';

    export let districtId;

    let result;
    let district;
    const sections = settings.select.sections;
    const groups = settings.select.groups;
    let years = settings.select.years;


    let sectionId = null;
    let year = null; //TODO should be null to start
    let group = null;
    let breeds = [];

    let changeCounter = 0; // counter for knowing if any still unsaved.


    function loadDistrict() {
        api.district.get( districtId ).then( response => { district = response.district } );
    }

    function loadBreeds() {
        console.log( 'loadBreeds', sectionId, year, group );
        breeds = []; // empty
        if( sectionId === 5 ) group = 'I'; // pigeons don't have group, so defaults to 'I' locally
        if( sectionId && year && group ) { // on change of any reload all
            console.log( 'Ready to get');
//            api.section.breeds.get( sectionId )
            api.district.results.section.get( districtId, sectionId, year, group ).then( response => {
                breeds = response.results }
            );
        }
    }

    function isValid( result ) {
        let valid =
            ( result.breeders == null    || ( result.breeders>=1 && result.breeders<=999999 ) )&&
            ( result.pairs == null       || ( result.pairs>=1 && result.pairs<=999999 ) )&&
            ( result.layDames===null     || ( result.layDames>=1 && result.layDames<=999999 ) ) &&
            ( result.layEggs===null      || ( result.layEggs>=1 && result.layEggs<=399 ) ) &&
            ( result.layWeight===null    || ( result.layWeight >=1 && result.layWeight<=999 ) ) &&

            ( result.broodEggs===null    || ( result.broodEggs >=1 && result.broodEggs<=999999 ) ) &&
            ( result.broodFertile===null || ( result.broodFertile >=0 && result.broodFertile<=result.broodEggs ) ) &&
            ( sectionId === 5
                ? ( result.broodHatched===null || ( result.broodHatched >=0 && result.broodHatched<=999999 ) ) // pigeons
                : ( result.broodHatched===null || ( result.broodHatched >=0 && result.broodHatched<=(result.broodFertile===null ? result.broodEggs : result.broodFertile ) ) )
            ) &&

            ( result.showCount===null     || ( result.showCount >0 && result.showCount <=999999 ) ) &&
            ( result.showScore===null    || ( result.showCount >0 && result.showScore >=89 && result.showScore<=97 ) );
        return valid;
    }

    const validator = {
        showCount: (result) => {
            const valid =
                (result.showCount === null && result.showScore === null) ||
                (result.showCount > 0 && result.showCount < 999999);

            console.log( 'Showcount valid', valid );
            return valid;
        }
    }

    const showCount = (result) => {
        console.log('v');
        return () => {
            console.log('validate showcount');
            const valid =
                (result.showCount === null && result.showScore === null) ||
                (result.showCount > 0 && result.showCount < 999999);

            console.log('Showcount valid', valid);
            return valid;
        }
    }

    function onQuery( route ) {
        console.log( 'OnQuery');
        sectionId = route.query.section ? Number( route.query.section ) : 3;
        year = route.query.year ? Number( route.query.year ) : new Date().getFullYear();
        group = route.query.group && [ 'I', 'II', 'III' ].includes( route.query.group ) ? route.query.group : 'I';
        loadDistrict();
        loadBreeds();
        changeCounter = 0;
    }

    function onSection( event ) {
        router.location.query.set( 'section', sectionId );
    }
    function onYear( event ) {
        router.location.query.set( 'year', year );
    }
    function onGroup( event ) {
        router.location.query.set( 'group', group );
    }

    function onOpen( breed ) {
        return ( event ) => {
            console.log('Open');
            if (breed.open) {
                let changed = false;
                for (let result of breed.colors) {
                    changed |= result.changed;
                }
                if (changed) {
                    // Breed.open = true; // stay open until saved
                } else {
                    breed.open = false;
                    breeds = breeds; // trigger
                }

            } else { // to open
                console.log('Fetch', breed, districtId, sectionId, year, group);
                if (sectionId === 5) group = 'I'; // pigeons don't have group, so defaults to 'I' locally
                if (breed.id && districtId && sectionId && year && group) {
                    console.log('Getting Breed results');
                    api.district.results.breed.get(districtId, sectionId, breed.id, year, group)
                        .then(response => {
                            breed.colors = response.results
                            breed.open = true;
                            breeds = breeds; // trigger
                            breed = breed;
                        })
                }
            }
        }
    }
    function onResultChange( breed, result ) {
        return ( event ) => {
            if( result.changed !== true ) {
                changeCounter++
                result.changed = true; // changing the data object here !
 //               breeds = breeds; // trigger to redraw
            }
        }
    }

    function onPigeonBroodsChange( result, broods ) { // calc eggs from broods
        return ( e ) => {
            let value = e.target.value;
            if( value ) {
                result.broodEggs = value * 2;
                //result.broodFertile = result.broodEggs;
            } else {
                result.broodEggs = null;
                result.broodFertile = null;
            }
        }
    }

    function onSave( result ) {
        return ( event ) => {
            console.log('Saved', result );
            result.changed = false; // asap

            api.result.post( result ).then( ( response ) => {
                result.id = response.id; // new id when inserted
                changeCounter--;
//                breeds = breeds; // trigger
            } );
        }
    }

    function onSubmit( event ) {
        console.log( 'Submit' );
    }

    $: onQuery( $router );
//    $: getDistrict( districtId );
//    $: getBreeds( sectionId, year, group );

    console.log( 'ResultInputHeader here');

</script>

<h2 class='w-256 text-center'>Zuchtbuch Leistungen {district ? district.name : '...'}</h2>
<div class='w-256 justify-center flex flex-row mx-2 gap-x-4'>

    <Select label="Jahr" bind:value={year} on:change={onYear}>
        <option value={null}></option>
        {#each years as year}
            <option value={year}>{year}</option>
        {/each}
    </Select>

    <Select label="Sparte" bind:value={sectionId} on:change={onSection}>
        <option value={null}></option>
        {#each sections as section}
            <option value={section.id}>{section.name}</option>
        {/each}
    </Select>

    {#if sectionId && sectionId !== 5}
        <Select label="Gruppe" bind:value={group} on:change={onGroup}>
            <option value={null}></option>
            {#each groups as group}
                <option value={group}>{group}</option>
            {/each}
        </Select>
    {/if}


</div>

{#if sectionId }
    <div class='w-256 flex flex-col bg-header rounded-t'>
        <div class='flex flex-row p-2 gap-x-1 text-sm font-bold'>
            <div class='w-64'>Rasse {sectionId === 5 ? '' : ' & Farbe'}</div>
            <div class='w-4'></div>

            <div class='w-28 text-center'>Zucht</div>

            <div class='w-4 text-center'>|</div>

            {#if sectionId !== 5 }
                <div class='w-44 text-center'>Legeleistung</div>
                <div class='w-1 text-center'>|</div>
            {/if}

            {#if sectionId === 5 }
                <div class='w-28 text-center'>Brutleistung</div>
                <div class='w-4 text-center'>|</div>
            {:else}
                <div class='w-44 text-center'>Brutleistung</div>
                <div class='w-3 text-center'>|</div>
            {/if}

            <div class='w-28 text-center'>Schauleistung</div>

            {#if changeCounter > 0 }
                <div class='px-4 text-red-600'> # { changeCounter }</div>
            {/if}
        </div>
    </div>


    <div class='w-256 bg-gray-100 overflow-y-scroll border border-t-gray-400 rounded-b scrollbar'>
        {#if breeds }
            {#each breeds as breed }
                <div class='flex flex-row px-2 gap-x-1'>
                    <div class='w-64 cursor-pointer whitespace-nowrap' on:click={onOpen(breed)} >
                        {breed.name} {#if breed.results } <span class='text-xs'>({breed.results})</span> {/if}
                    </div>

                    <div class='w-4'></div>

                    {#if breed.open }
                        {#if sectionId === 5 }
                            <div class='flex flex-row gap-x-1 text-xs text-center'>
                                <div class='w-14'>Zuchten</div>
                                <div class='w-14'>Paare</div>

                                <div class='w-2 text-center'> | </div>

                                <div class='w-14'>Bruten</div>
                                <div class='w-14'>Küken</div>

                                <div class='w-2 text-center'> | </div>

                                <div class='w-14'>Tiere</div>
                                <div class='w-14 whitespace-nowrap'>Note</div>
                                <div class='w-14'></div>
                            </div>
                        {:else}
                            <div class='flex flex-row gap-x-1 text-xs text-center'>
                                <div class='w-14'>Zuchten</div>
                                <div class='w-14'>Stämme</div>

                                <div class='w-2 text-center'>|</div>

                                <div class='w-14'>Hennen</div>
                                <div class='w-14'>Eier/J</div>
                                <div class='w-14 whitespace-nowrap'>Gewicht</div>

                                <div class='w-2 text-center'>|</div>

                                <div class='w-14'>Eingelegt</div>
                                <div class='w-14'>Befruchtet</div>
                                <div class='w-14'>Geschlüpft</div>

                                <div class='w-2 text-center'>|</div>

                                <div class='w-14'>Tiere</div>
                                <div class='w-14 whitespace-nowrap'>Note</div>
                            </div>
                        {/if}
                    {/if}
                </div>


                {#if breed.open }
                    {#each breed.colors as result}

                        <form class='flex flex-row px-2 gap-x-1 text-base' on:change={onResultChange( breed, result )}>
                            {#if sectionId === 5 }
                                <div class='w-4 pl-2'>&#10551; </div>
                                <div class='w-64'>Gesamte Farbenschläge</div>

                                <InputNumber class='w-14' bind:value={result.breeders} min=1 max=99999 error='1..99999' />
                                <InputNumber class='w-14' bind:value={result.pairs} min=1 max=99999 error='1..99999' />

                                <div class='w-2'></div>

                                <InputNumber class='w-14' value={result.broodEggs ? result.broodEggs/2 : null} min=0 max=99999 on:input={onPigeonBroodsChange( result )}/>
                                <InputNumber class='w-14' bind:value={result.broodHatched} min=0 max=99999 />

                                <div class='w-2'></div>

                                <InputNumber class='w-14' bind:value={result.showCount} min=1 max=9999 error='0..9999'/>
                                <InputNumber class='w-14' bind:value={result.showScore} min=90 max=97 step=0.1 error='90..97'/>
                            {:else}
                                <div class='w-4 pl-2'>&#10551; </div>
                                <div class='w-64'>{result.name}</div>

                                <InputNumber class='w-14' bind:value={result.breeders} min=1 max=99999 error='1..99999' />
                                <InputNumber class='w-14' bind:value={result.pairs} min=1 max=99999 error='1..99999' />

                                <div class='w-2'></div>

                                <InputNumber class='w-14' bind:value={result.layDames} min=1 max=99999 error='0..99999'/>
                                <InputNumber class='w-14' bind:value={result.layEggs} min=0 max=399 error='0..399'/>
                                <InputNumber class='w-14' bind:value={result.layWeight} min=1 max=999 error='0..999'/>

                                <div class='w-2'></div>

                                <InputNumber class='w-14' bind:value={result.broodEggs} min=1 max=99999 error='0..99999'/>
                                <InputNumber class='w-14' bind:value={result.broodFertile} min=0 max={result.broodEggs} error='0..{result.broodEggs}'/>
                                <InputNumber class='w-14' bind:value={result.broodHatched} min=0 max={(result.broodFertile==null ? result.broodEggs : result.broodFertile )} error='0..{result.broodFertile}'/>

                                <div class='w-2'></div>

                                <InputNumber class='w-14' bind:value={result.showCount} min=1 max=99999 error='0..99999' validator={showCount(result)}/>
                                <InputNumber class='w-14' bind:value={result.showScore} min=89 max=97 step=0.1 error='89..97'/>

                                <div class='w-2'></div>
                            {/if}

                            {#if result.changed }
                                {#if ! result.breeders }
                                    <input class='bg-yellow-500 px-2 cursor-pointer' type='button' value='&#10006;' on:click={onSave(result)}>
                                {:else if isValid( result ) }
                                    <Button class='bg-green-100 px-2 text-green-700' value='&#10004;' on:click={onSave(result)} />
                                {:else}
                                    <Button class='bg-red-100 px-2 text-red-700' value='-' alert={true}/>
                                {/if}
                            {/if}
                        </form>
                    {/each}
                {/if}
            {/each}
        {/if}
    </div>
{/if}



<style>
    input {
        @apply h-6 border border-gray-400 rounded;
    }

</style>