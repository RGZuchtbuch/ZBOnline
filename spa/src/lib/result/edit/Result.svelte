<script>
    import api         from '../../../js/api.js';
    import NumberInput from '../../common/input/Number.svelte';

    export let sectionId = null;
    export let result = null;
    export let saveCount = 0;
    let hasResult = false;

    let invalids = { // hold keys->true/false for validity per field
    }

    function onFormChange() {
        if( ! result.changed ) {
            result.changed = true; // use result.changed instead of result to keep changed after open/close/open of breed
            saveCount++;
        }
        // VALIDATE ALL
        invalids.breeders     = result.breeders !== null && ( result.breeders < 1 || result.breeders > 99999 );
        invalids.pairs        = result.pairs !== null && ( result.pairs < result.breeders || result.pairs > 99999 );

        invalids.layDames     = result.layDames !== null && ( result.layDames < 1 || result.layDames > 999999 );
        invalids.layEggs      = result.layEggs !== null && ( result.layEggs < 0 || result.layEggs > 365 );
        invalids.layWeight    = result.layWeight !== null && ( result.layWeight < 1 || result.layWeight > 999 );

        invalids.broodEggs    = result.broodEggs !== null && ( result.broodEggs < 1 || result.broodEggs > 999999 );
        invalids.broodFertile = result.broodFertile !== null && ( result.broodFertile < 1 || result.broodFertile > result.broodEggs );
        if( sectionId === 5 ) {
            invalids.broodHatched = result.broodHatched !== null && ( result.pairs === null || result.broodHatched < 1 || result.broodHatched > 999999 );
        } else {
            invalids.broodHatched = result.broodHatched !== null && (result.broodHatched < 1 || result.broodHatched > result.broodFertile);
        }
        invalids.showCount    = ( result.showCount === null && result.showScore !== null ) || ( result.showCount !== null && (result.showCount <=0 || result.showCount >= 999999 ));
        invalids.showScore    = ( result.showScore !== null && ( result.showScore < 89 || result.showScore > 97 ) ) || ( result.showCount !== null && result.showScore === null );
        // || ( result.showCount >= 1 && result.showCount <= 999999 && results.showScore >= 89 && result.showScore <= 97 );

        invalids.form = false;
        for( let invalid in invalids ) {
            if( invalids[ invalid ] ) {
                invalids.form = true;
            }
        }
        invalids = invalids;
        console.log( 'OnFormChange', result.breeders, invalids.form );
    }

    function onSave() {
        console.log('onSave' );
//        changed = false; // asap
        result.changed = false;
        saveCount--;

        api.result.post( result ).then( ( response ) => {
            result.id = response.id; // new id when inserted
        });
    }

    function onDelete() {
        console.log( 'OnDelete' );
        onSave();
        for( let key in result ) { // clear result
            result.pairs = null;
            result.layDames = result.layEggs = result.layWeight = null;
            result.broodEggs = result.broodFertile = result.broodHatched = null;
            result.showCount = result.showScore = null;
            invalids = {};
        }

    }

    $: hasResult = result.breeders > 0;

</script>


<form class='flex flex-row px-2 gap-x-1 text-sm' on:input={onFormChange}>
    <div class='w-4 pl-2'>&#10551; </div>
    <div class='w-64' class:hasResult>{result.name}</div>

    <NumberInput class='w-14' bind:value={result.breeders} min=1 max=99999 error='1..99999' title='Zahl der Zuchten/Züchter, leer lassen zum Löschen' invalid={invalids.breeders} />
    <NumberInput class='w-14' bind:value={result.pairs} min=1 max=99999 error={ (result.breeders ? result.breeders : '1')+'..99999'} title='Zahl der Stämme/Paare' invalid={invalids.pairs} />

    <div class='w-2'></div>

    {#if sectionId === 5}
        <div class='w-14'></div> <div class='w-14'></div> <div class='w-14'></div>
    {:else}
        <NumberInput class='w-14' bind:value={result.layDames} min=1 max=999999 error='0..99999' title='Gesamtzahl der legende Hennen' invalid={invalids.layDames}/>
        <NumberInput class='w-14' bind:value={result.layEggs} min=0 max=399 error='0..399' title='Durchschnittslegeleistung' invalid={invalids.layEggs}/>
        <NumberInput class='w-14' bind:value={result.layWeight} min=1 max=999 error='1..999' title='Durchschnittsgewicht der gelegten Eier' invalid={invalids.layWeight}/>
    {/if}

    <div class='w-2'></div>

    {#if sectionId === 5}
        <div class='w-14'></div>
        <div class='w-14'></div>
        <NumberInput class='w-14' bind:value={result.broodHatched} min=0 max=99999 error='0..999999' title='Geschlüpfte Küken, Braucht Paare' invalid={invalids.broodHatched}/>
    {:else}
        <NumberInput class='w-14' bind:value={result.broodEggs} min=1 max=99999 error='0..99999' title='Eigelegte Eier' invalid={invalids.broodEggs}/>
        <NumberInput class='w-14' bind:value={result.broodFertile} min=0 max={result.broodEggs} error='0..{result.broodEggs}' title='Befruchtete Eier, nicht mehr als eingelegt' invalid={invalids.broodFertile}/>
        <NumberInput class='w-14' bind:value={result.broodHatched} min=0 max={(result.broodFertile==null ? result.broodEggs : result.broodFertile )} error='0..{result.broodFertile}' title='Geschlüpfte Küken, nicht mehr als befruchtet oder eingelegt' invalid={invalids.broodHatched}/>
    {/if}

    <div class='w-2'></div>

    <NumberInput class='w-14' bind:value={result.showCount} min=1 max=999999 error='1..99999' invalid={invalids.showCount} title='Zahl der ausgestellten Tiere'/>
    <NumberInput class='w-14' bind:value={result.showScore} step=0.1 min=89 max=97 error='89..97' invalid={invalids.showScore} title='Durchschnittsbewertung u/o=89, 90..97 Punkte, braucht Zahl der ausgestellen Tiere'/>

    <div class='w-2'></div>


    {#if result.changed }
        <div class='' >
            {#if ! result.breeders }
                <input class='w-8 px-2 cursor-pointer' type='button' value='&#10006;' title='Löschen wenn Zuchten Leer' on:click={onDelete}>
            {:else if invalids.form }
                <input class='w-8 px-2 cursor-not-allowed' type='button' value='!' title='Nur speichern ohne Fehler!'>
            {:else}
                <input class='w-8 cursor-pointer text-base font-bold' type='button' value='💾' title='Speichern' on:click={onSave}>
            {/if}
        </div>
    {/if}
</form>

<style>
    .hasResult {
        @apply font-bold;
    }
    input[type='button'] {
        @apply bg-alert text-white m-0 p-0;
    }
</style>