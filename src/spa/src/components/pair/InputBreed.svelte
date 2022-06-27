<script>
    import api from "../../scripts/api";

    export let breed;
    export let disabled=true;

    let sectionId = breed.sectionId;
    let breedId = breed.breedId;
    let colorId = breed.colorId;

    // for selecting breed & color
    let sections = [];
    let breeds = [];
    let colors = [];

    function loadSections() {
        console.log( 'Load Sections' );
        api.section.getChildren( 2 ).then( data => {
            sections = data;
        });
    }

    function onChangeSection( sectionId ) {
        if( sectionId ) {
            console.log( 'CS', sectionId, breedId, colorId );
            api.section.getBreeds(sectionId).then(data => {
                breeds = data;
                if( sectionId !== breed.sectionId) { // keep current id's for first load
                    breed.sectionId = sectionId;
                    breedId = null;
                    colors = [];
                    colorId = null;
                }
            });
        } else {
            breeds = [];
            breedId = null;
            colors = [];
            colorId = null;
        }
    }

    function onChangeBreed( breedId ) {
        if( breedId) {
            api.breed.getColors( breedId ).then(data => {
                colors = data;
                if( breedId !== breed.breedId ) { //check for new breedId
                    breed.breedId = breedId;
                    colorId = null;
                }
                if (colorId === null && colors.length === 1) {
                    colorId = colors[0].id; // set to only choice
                }
            });
        } else {
            colors = [];
            colorId = null;
        }
    }


    function onChangeColor( colorId ) {
        if( colorId ) {
            breed.colorId = colorId;
        }
    }

    loadSections();
    $: onChangeSection( sectionId );
    $: onChangeBreed( breedId );
    $: onChangeColor( colorId );

</script>

<div class='w-full flex flex-row p-2 gap-2'>
    <div class='flex flex-col border border-b-red-600'>
        <div class='text-xs text-gray-600'>Sparte {sectionId?'['+sectionId+']':'x'}</div>
        <select class='w-32' bind:value={sectionId} {disabled}>
            <option value={null}>?</option>
            {#each sections as section}
                <option value={section.id}>{section.name}</option>
            {/each}
        </select>
    </div>

    <div class='flex flex-col border border-b-red-600'>
        <div class='text-xs text-gray-600'>Rasse {breedId?'['+breedId+']':'x'}</div>
        <select class='w-64' bind:value={breedId} disabled={disabled || ! sectionId}>
            <option value={null}>? ({breeds.length})</option>
            {#if breeds }
                {#each breeds as breed}
                    <option value={breed.id}>{breed.name}</option>
                {/each}
            {/if}
        </select>
    </div>

    <div class='flex flex-col border border-b-red-600'>
        <div class='text-xs text-gray-600'>Farbe {colorId?'['+colorId+']':'x'}</div>
        <select class='w-64' bind:value={colorId} disabled={disabled || ! breedId }>
            <option value={null}>? ({colors.length})</option>
            {#if colors}
                {#each colors as color}
                    <option value={color.id}>{color.name}</option>
                {/each}
            {/if}
        </select>
    </div>
</div>

<style>

</style>
