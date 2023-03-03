<script>
    import {router} from "tinro";
    import api from '../../js/api.js';
    import InputNumber from '../input/Number.svelte';
    import Select from '../input/Select.svelte';

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

    let resultsChanged = []; // counter for knowing if any still unsaved.





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
            ( result.showCount==null     || ( result.showCount >0 && result.showCount <=999999 ) ) &&
            ( result.showScore===null    || ( result.showCount >0 && result.showScore >=89 && result.showScore<=97 ) );
        console.log( 'isValid', valid );
        return valid;
    }

    function onQuery( route ) {
        console.log( 'OnQuery');
        sectionId = route.query.section ? Number( route.query.section ) : 3;
        year = route.query.year ? Number( route.query.year ) : new Date().getFullYear();
        group = route.query.group && [ 'I', 'II', 'III' ].includes( route.query.group ) ? route.query.group : 'I';
        loadDistrict();
        loadBreeds();
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
                for (let result of breed.results) {
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
                            breed.results = response.results
                            breed.open = true;
                            breeds = breeds; // trigger
                        })
                }
            }
        }
    }
    function onResultChange( breed, result ) {
        return ( event ) => {
            result.changed = true; // changing the data object here !
            resultsChanged[ result ] = true;
            breeds = breeds; // trigger to redraw
            console.log( 'Changed', Object.keys( resultsChanged ).length );
        }
    }
    function onSave( result ) {
        return ( event ) => {
            console.log('Saved', result );

            api.result.post( result ).then( ( response ) => {
                result.id = response.id; // new id when inserted
                result.changed = false;
                delete resultsChanged[ result ];
                resultsChanged = resultsChanged;
                console.log( 'Changed', Object.keys( resultsChanged ).length );
                breeds = breeds; // trigger
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

<h2 class='text-center'>Zuuuchtbuch Leistungen {district ? district.name : '...'}</h2>
<div class='border-b border-gray-400 justify-center flex flex-row mx-2 gap-x-4'>
    <Select label="Sparte" bind:value={sectionId} on:change={onSection}>
        <option value={null}></option>
        {#each sections as section}
            <option value={section.id}>{section.name}</option>
        {/each}
    </Select>

    <Select label="Jahr" bind:value={year} on:change={onYear}>
        <option value={null}></option>
        {#each years as year}
            <option value={year}>{year}</option>
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

    <span>Nicht gespeichert # {Object.keys( resultsChanged ).length}</span>
</div>

{#if sectionId }
    <div class='flex flex-col bg-header'>
        <div class='flex flex-row p-2 gap-x-1 text-sm font-bold'>

            {#if sectionId === 5 }
                <div class='w-16'>Rasse</div>
                <div class='w-72'></div>

                <div class='w-16'>Zuchten</div>
                <div class='w-16'>Paare</div>

                <div class='w-2'></div>

                <div class='w-16'>Soll Eier</div>
                <div class='w-16'>Küken</div>
                <div class='w-16 whitespace-nowrap'>∅ Paar</div>

                <div class='w-2'></div>

                <div class='w-16'>Tiere</div>
                <div class='w-16 whitespace-nowrap'>∅ Note</div>
                <div class='w-16'></div>
            {:else}
                <div class='w-16'>Rasse</div>
                <div class='w-72'>Farbe</div>

                <div class='w-16'>Zuchten</div>
                <div class='w-16'>Paare</div>

                <div class='w-2'></div>

                <div class='w-16'>Hennen</div>
                <div class='w-16'>Eier/J</div>
                <div class='w-16 whitespace-nowrap'>∅ Gewicht</div>

                <div class='w-2'></div>

                <div class='w-16'>Eingelegt</div>
                <div class='w-16'>Befruchtet</div>
                <div class='w-16'>Geschlüpft</div>

                <div class='w-2'></div>

                <div class='w-16'>Tiere</div>
                <div class='w-16 whitespace-nowrap'>∅ Note</div>
                <div class='w-16'></div>
            {/if}
        </div>
    </div>


    <div class='bg-gray-100 overflow-y-scroll border border-gray-600 border-t-gray-400 rounded-b scrollbar'>
        {#if breeds }
            {#each breeds as breed }
                <div class='flex flex-row px-2 gap-x-1'>
                    <div class='w-72 cursor-pointer' on:click={onOpen(breed)} >{breed.name}</div>
                    <div class='w-16 cursor-pointer'></div>
                    {#if breed.open }
                        {#if sectionId === 5 }
                            <div class='flex flex-row gap-x-1 text-xs'>
                                <div class='w-16'>Zuchten</div>
                                <div class='w-16'>Paare</div>

                                <div class='w-2'></div>

                                <div class='w-16'>Soll Eier</div>
                                <div class='w-16'>Küken</div>
                                <div class='w-16 whitespace-nowrap'>∅ pro Paar</div>

                                <div class='w-2'></div>

                                <div class='w-16'>Tiere</div>
                                <div class='w-16 whitespace-nowrap'>∅ Note</div>
                                <div class='w-16'></div>
                            </div>
                        {:else}
                            <div class='flex flex-row gap-x-1 text-xs'>
                                <div class='w-16'>Zuchten</div>
                                <div class='w-16'>Paare</div>

                                <div class='w-2'></div>

                                <div class='w-16'>Hennen</div>
                                <div class='w-16'>Eier/J</div>
                                <div class='w-16 whitespace-nowrap'>∅ Gewicht</div>

                                <div class='w-2'></div>

                                <div class='w-16'>Eingelegt</div>
                                <div class='w-16'>Befruchtet</div>
                                <div class='w-16'>Geschlüpft</div>

                                <div class='w-2'></div>

                                <div class='w-16'>Tiere</div>
                                <div class='w-16 whitespace-nowrap'>∅ Note</div>
                                <div class='w-16'></div>
                            </div>
                        {/if}
                    {/if}
                </div>


                {#if breed.open }
                    {#each breed.results as result}

                        <form class='flex flex-row px-2 gap-x-1 text-base' on:change={onResultChange( breed, result )}>
                            {#if sectionId === 5 }
                                <div class='w-8 pl-4'>&#10551; </div>
                                <div class='w-80'>Gesamte Farbenschläge</div>

                                <InputNumber class='w-16' bind:value={result.breeders} min=1 max=99999 error='1..99999' />
                                <InputNumber class='w-16' bind:value={result.pairs} min=1 max=99999 error='1..99999' />

                                <div class='w-2'></div>

                                <InputNumber class='w-16' bind:value={result.broodEggs} min=0 max=99999 />
                                <InputNumber class='w-16' bind:value={result.broodHatched} min=0 max=99999 />
                                <InputNumber class='w-16' value={result.pairs ? result.broodHatched / result.pairs : '-' } readonly />

                                <div class='w-2'></div>

                                <InputNumber class='w-16' bind:value={result.showCount} min=1 max=9999 error='0..9999'/>
                                <InputNumber class='w-16' bind:value={result.showScore} min=90 max=97 step=0.1 error='90..97'/>
                            {:else}
                                <div class='w-8 pl-4'>&#10551; </div>
                                <div class='w-80'>{result.name}</div>

                                <InputNumber class='w-16' bind:value={result.breeders} min=1 max=99999 error='1..99999' />
                                <InputNumber class='w-16' bind:value={result.pairs} min=1 max=99999 error='1..99999' />

                                <div class='w-2'></div>

                                <InputNumber class='w-16' bind:value={result.layDames} min=1 max=99999 error='0..99999'/>
                                <InputNumber class='w-16' bind:value={result.layEggs} min=0 max=399 error='0..399'/>
                                <InputNumber class='w-16' bind:value={result.layWeight} min=1 max=999 error='0..999'/>

                                <div class='w-2'></div>

                                <InputNumber class='w-16' bind:value={result.broodEggs} min=1 max=99999 error='0..99999'/>
                                <InputNumber class='w-16' bind:value={result.broodFertile} min=0 max={result.broodEggs} error='0..{result.broodEggs}'/>
                                <InputNumber class='w-16' bind:value={result.broodHatched} min=0 max={(result.broodFertile==null ? result.broodEggs : result.broodFertile )} error='0..{result.broodFertile}'/>

                                <div class='w-2'></div>

                                <InputNumber class='w-16' bind:value={result.showCount} min=1 max=99999 error='0..99999'/>
                                <InputNumber class='w-16' bind:value={result.showScore} min=89 max=97 step=0.1 error='89..97'/>
                            {/if}

                            {#if result.changed }
                                {#if isValid( result ) }
                                    <div class='w-16 text-green-600' on:click={onSave( result )}>[Save]</div>
                                {:else}
                                    <div class='w-16 text-red-600'>[Save]</div>
                                {/if}
                            {:else}
                                <div class='w-16'></div>
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