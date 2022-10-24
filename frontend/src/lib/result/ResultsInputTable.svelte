<script>
    import { onMount } from 'svelte';
    import { active, meta, router, Route} from 'tinro';
    import { dec, perc } from '../../js/util.js';

    import api from '../../js/api.js';

    import InputNumber from '../input/Number.svelte';
    import InputText   from '../input/Text.svelte';
    import Select from '../select/Select.svelte';
    import BreedSelect from '../select/Breed.svelte';

    export let legend = '';
    export let link='';
    export let districtId = null;
    export let sectionId = null;
    export let year = 2021;
    export let group = 'I';

    let district = null;

    // for treelike
    let current = { breedId:null };
    let header = null;

    $: getResults( sectionId, year, group );

    function getResults( sectionId, year, group ) {
        console.log( sectionId, year, group );
        if( sectionId && year && group ) api.district.results.full.get( districtId, sectionId, year, group )
            .then( response => {
                district = response.district;
            })
    }

    function onSubmit( event ) {
        console.log( 'Submitting all valid rows')
    }

    function edit() {}
    function clear( result ) {
        if( result.sectionId === 5) {
            // pigeons
        } else {
            // rest
        }
    }

    function open( breed ) {
        console.log( "open", breed.name );
        breed.open = ! breed.open;
        district = district; // trigger
    }

    function isValid( result ) { // TODO
        result.valid = result.breeders || result.layDames || result.layEggs || result.layWeight || result.broodEggs || result.broodFertile || result.broodHatched || result.showCount || result.showScore;
        if( result.valid && !header.changed ) {
            header.changed = true;
            //results = results;
        }
        return result.valid;
    }
    console.log( 'ResultsInputTable here');

</script>

<form class='flex flex-col' on:submit|preventDefault={onSubmit}>
    <div>Rassen und Leistungen</div>
    <div class='flex flex-row gap-x-1 text-xs'>
        <div class='w-8'>Rasse</div>
        <div class='w-72'>Farbe</div>

        <div class='w-12'>Zuchten</div>

        <div class='w-4'></div>

        <div class='w-12'>Hennen</div>
        <div class='w-10'>Eier/J</div>
        <div class='w-14'>Eiggewicht</div>

        <div class='w-4'></div>

        <div class='w-12'>Eingelegt</div>
        <div class='w-14'>Befruchtet</div>
        <div class='w-14'>Geschlüpft</div>

        <div class='w-4'></div>

        <div class='w-10'>Tiere</div>
        <div class='w-14'>Bewertung</div>
    </div>
    {#if district }
        {#each district.section.breeds as breed }
            <div class='flex flex-row gap-x-1 text-xs'>
                <div class='w-72 cursor-pointer' on:click={open(breed)} >{breed.name}</div>
                <div class='w-8'></div>
                {#if breed.open }
                    <div class='w-12'>Zuchten</div>

                    <div class='w-4'></div>

                    <div class='w-12'>Hennen</div>
                    <div class='w-10'>Eier/J</div>
                    <div class='w-14'>Eiggewicht</div>

                    <div class='w-4'></div>

                    <div class='w-12'>Eingelegt</div>
                    <div class='w-14'>Befruchtet</div>
                    <div class='w-14'>Geschlüpft</div>

                    <div class='w-4'></div>

                    <div class='w-10'>Tiere</div>
                    <div class='w-14'>Bewertung</div>
                {/if}
            </div>
            {#if breed.open }
                {#each breed.colors as color}
                    <div class='flex flex-row gap-x-1 text-xs'>
                        <div class='w-8'></div>
                        <div class='w-72'>{color.name}</div>

                        <input class='w-12' bind:value={color.result.breeders} >

                        <div class='w-4'></div>

                        <input class='w-12' bind:value={color.result.lay.dames} >
                        <input class='w-10' bind:value={color.result.lay.eggs} >
                        <input class='w-14' bind:value={color.result.lay.weight} >

                        <div class='w-4'></div>

                        <input class='w-12' bind:value={color.result.brood.eggs} >
                        <input class='w-14' bind:value={color.result.brood.fertile} >
                        <input class='w-14' bind:value={color.result.brood.hatched} >

                        <div class='w-4'></div>

                        <input class='w-10' bind:value={color.result.show.count} >
                        <input class='w-14' bind:value={color.result.show.score} >
                    </div>
                {/each}
            {/if}
        {/each}
    {/if}
</form>


<style>
    input {
        @apply h-6 border border-gray-400 rounded;
    }

</style>