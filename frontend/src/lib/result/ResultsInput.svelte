<script>
    import api from '../../js/api.js';
    import InputNumber from '../input/Number.svelte';
    import Select from '../select/Select.svelte';


    export let legend = '';
    export let link='';
    export let promise = null;

    let district = null;
    let sections = [];
    let groups = [];
    let years = [ 2022, 2021, 2020, 2019, 2018, 2017 ];

    let sectionId = null;
    let year = null; //TODO should be null to start
    let group = null;
    let breeds = [];


    getSections();
    getGroups();

    $: handle( promise );



    $: getBreeds( district, sectionId, year, group );

/**  functions **/
    function handle( promise ) {
        promise.then( (data) => {
            district = data.district;
        })
    }

//    function getDistrict() {
//        api.district.get( districtId ).then( response => { district = response.district } );
//    }
    function getSections() {
        api.section.children.get(2).then( response => {
            sections = response.sections
            console.log( 'Sections', sections );
        } );
    }
    function getGroups() {
        api.groups.get().then( response => { groups = response.groups } );
    }
    function getBreeds(district, sectionId, year, group ) {
        if( sectionId === 5 ) group = 'I'; // pigeons don't have group, so defaults to 'I' locally
        if( district && sectionId && year && group ) {
            console.log( 'Ready to get');
            api.result.breeds.get( district.id, sectionId, year, group )
                .then( response => { breeds = response.breeds } );
        }
    }

    function isValid( result ) {
        let valid =
            ( result.breeders===null  || ( result.breeders>= 1 && result.breeders<=9999 ) ) &&
            ( result.pairs===null        || ( result.pairs>=1 && result.pairs<=9999 ) ) &&
            ( result.layDames===null     || ( result.layDames>=1 && result.layDames<=9999 ) ) &&
            ( result.layEggs===null      || ( result.layEggs>=1 && result.layEggs<=399 ) ) &&
            ( result.layWeight===null    || ( result.layWeight >=1 && result.layWeight<=999 ) ) &&
            ( result.broodEggs===null    || ( result.broodEggs >=1 && result.broodEggs<=9999 ) ) &&
            ( result.broodFertile===null || ( result.broodFertile >=0 && result.broodFertile<=result.broodEggs ) ) &&
            ( sectionId === 5
                ? ( result.broodHatched===null || ( result.broodHatched >=0 && result.broodHatched<=9999 ) )
                : ( result.broodHatched===null || ( result.broodHatched >=0 && result.broodHatched<=(result.broodFertile===null ? result.broodEggs : result.broodFertile ) ) )
            ) &&
            ( result.showCount==null     || ( result.showCount >=1 && result.showCount<=999 ) ) &&
            ( result.showScore===null    || ( result.showScore >=1 && result.showScore<=999 ) );
        console.log( 'isValid', valid );
        return valid;
    }

    function onOpen( breed ) {
        if( breed.open ) {
            let changed = false;
            for( let result of breed.results ) {
                changed |= result.changed;
            }
            if( changed ) {
                // breed.open = true; // stay open until saved
            } else {
                breed.open = false;
                breeds = breeds; // trigger
            }

        } else { // open
            if( sectionId === 5 ) group = 'I'; // pigeons don't have group, so defaults to 'I' locally
            if( breed.id && district.id && sectionId && year && group ) {
                api.result.breed.get(breed.id, district.id, year, group)
                    .then(response => {
                        breed.results = response.results
                        breed.open = true;
                        breeds = breeds; // trigger
                    })
            }
        }
    }
    function onResultChange( breed, result ) {
        return ( event ) => {
            result.changed = true;
            breeds = breeds; // trigger
            //console.log( 'Changed', result );
        }
    }
    function onSave( result ) {
        return ( event ) => {
            console.log('Saved', result );

            api.result.post( result ).then( ( response ) => {
                result.id = response.id; // new id when inserted
                result.changed = false;
                breeds = breeds; // trigger
            } );
        }
    }
    function onSubmit( event ) {
        console.log( 'Submit' );
    }

    console.log( 'ResultInputHeader here');

</script>

<div class='flex flex-col'>
    <h3 class='text-center'>Zuchtbuch Leistungen {district ? district.name : '...'}</h3>
    <div class='border-b border-gray-400 justify-center flex flex-row mx-2 gap-x-4'>
        <Select label="Sparte" bind:value={sectionId}>
            <option value={null}></option>
            {#each sections as section}
                <option value={section.id}>{section.name}</option>
            {/each}
        </Select>

        <Select label="Jahr" bind:value={year}>
            <option value={null}></option>
            {#each years as year}
                <option value={year}>{year}</option>
            {/each}
        </Select>

        {#if sectionId !== 5}
            <Select label="Gruppe" bind:value={group}>
                <option value={null}></option>
                {#each groups as group}
                    <option value={group}>{group}</option>
                {/each}
            </Select>
        {/if}
    </div>

    {#if sectionId}
        <form class='flex flex-col' on:submit|preventDefault={onSubmit}>
            <div class='flex flex-row gap-x-1 text-xs'>

                {#if sectionId === 5 }
                    <div class='w-16'>Rasse</div>
                    <div class='w-72'></div>

                    <div class='w-16'>Zuchten</div>
                    <div class='w-16'>Paare</div>

                    <div class='w-2'></div>

                    <div class='w-16'>Jungtiere</div>
                    <div class='w-16 whitespace-nowrap'>∅ pro Paar</div>

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
            {#if district && breeds }
                {#each breeds as breed }
                    <div class='flex flex-row gap-x-1 text-xs'>
                        <div class='w-16 cursor-pointer' on:click={onOpen(breed)} >{breed.id}</div>
                        <div class='w-72 cursor-pointer' on:click={onOpen(breed)} >{breed.name}</div>
                        {#if breed.open }
                            {#if sectionId === 5 }
                                <div class='w-16'>Zuchten</div>
                                <div class='w-16'>Paare</div>

                                <div class='w-2'></div>

                                <div class='w-16'>Jungtiere</div>
                                <div class='w-16 whitespace-nowrap'>∅ pro Paar</div>

                                <div class='w-2'></div>

                                <div class='w-16'>Tiere</div>
                                <div class='w-16 whitespace-nowrap'>∅ Note</div>
                                <div class='w-16'></div>
                            {:else}
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
                        {/if}
                    </div>
                    {#if breed.open }
                        {#each breed.results as result}

                            <form class='flex flex-row gap-x-1 text-xs' on:change={onResultChange( breed, result )}>
                                {#if sectionId === 5 }
                                    <div class='w-16'></div>
                                    <div class='w-72'>Gesamte Farbenschläge</div>

                                    <InputNumber class='w-16' bind:value={result.breeders} min=1 max=9999 error='1..9999'/>
                                    <InputNumber class='w-16' bind:value={result.pairs} min=1 max=9999 error='1..9999'/>

                                    <div class='w-4'></div>

                                    <InputNumber class='w-16' bind:value={result.broodHatched} min=0 max=9999 />
                                    <InputNumber class='w-16' value={result.pairs ? result.broodHatched / result.pairs : '-' } readonly />

                                    <div class='w-4'></div>

                                    <InputNumber class='w-16' bind:value={result.showCount} min=1 max=9999 error='0..9999'/>
                                    <InputNumber class='w-16' bind:value={result.showScore} min=90 max=97 step=0.1 error='90..97'/>
                                {:else}
                                    <div class='w-16'></div>
                                    <div class='w-72'>{result.name}</div>

                                    <InputNumber class='w-16' bind:value={result.breeders} min=1 max=9999 error='1..9999'/>
                                    <InputNumber class='w-16' bind:value={result.pairs} min=1 max=9999 error='1..9999'/>

                                    <div class='w-4'></div>

                                    <InputNumber class='w-16' bind:value={result.layDames} min=1 max=9999 error='0..9999'/>
                                    <InputNumber class='w-16' bind:value={result.layEggs} min=0 max=399 error='0..399'/>
                                    <InputNumber class='w-16' bind:value={result.layWeight} min=1 max=999 error='0..999'/>

                                    <div class='w-4'></div>

                                    <InputNumber class='w-16' bind:value={result.broodEggs} min=1 max=9999 error='0..9999'/>
                                    <InputNumber class='w-16' bind:value={result.broodFertile} min=0 max={result.broodEggs} error='0..{result.broodEggs}'/>
                                    <InputNumber class='w-16' bind:value={result.broodHatched} min=0 max={(result.broodFertile==null ? result.broodEggs : result.broodFertile )} error='0..{result.broodFertile}'/>

                                    <div class='w-4'></div>

                                    <InputNumber class='w-16' bind:value={result.showCount} min=1 max=9999 error='0..9999'/>
                                    <InputNumber class='w-16' bind:value={result.showScore} min=90 max=97 step=0.1 error='90..97'/>
                                {/if}

                                {#if result.changed }
                                    {#if isValid( result ) }
                                        <div class='w-16' on:click={onSave( result )}>[Save]</div>
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
        </form>
    {/if}
</div>


<style>
    input {
        @apply h-6 border border-gray-400 rounded;
    }

</style>