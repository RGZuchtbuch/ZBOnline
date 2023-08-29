<script>
    import {onMount} from "svelte";
    import Brood from "./Brood.svelte";

    export let pair;
    export let disabled;
    export let invalid = false;

    let layer;
    let invalids = [];

    function newBrood( pairId ) {
        return { id:0, pairId:pairId, start:null, eggs:null, fertile:null, hatched:null, ringed:null, chicks:[ newChick( pairId, 0 ), newChick( pairId, 0 ) ] };
    }

    function newChick( pairId, broodId ) {
        return {pairId:pairId, broodId:broodId, ring:null};
    }

    function addBrood() {
        let brood = newBrood( pair.id );
        pair.broods = [...pair.broods, brood];
    }

    function init() {
        layer = pair.sectionId !== 5; // type of brood depends on this
        if( ! pair.broods ) pair.broods = [];
        while( pair.broods.length < 2 ) { // want at least 2
            addBrood( pair.id );
        }
        if( layer ) {
            //
        } else {
            for (const brood of pair.broods) {
                //brood.eggs = 2;
                //brood.fertile = null;
                while (brood.chicks.length < 2) {
                    brood.chicks = [...brood.chicks, newChick( pair.id, brood.id )]
                }
            }
        }
    }

    function onInput( event ) {
        validate();
        console.log( 'I', invalids );
    }

    function validate() {
        invalid = false;
        for( let value of invalids ) {
            invalid |= value;
        }
    }

    onMount( init );

</script>


<fieldset class='flex flex-col border rounded border-gray-400' on:input={onInput}>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Brutleistung</div>
        <button type='button' class='w-6 border rounded bg-header text-center text-white cursor-pointer' class:disabled title='Brut/Nest hinzufügen' on:click={addBrood}>✚</button>
    </div>

    <div class='flex flex-col p-2 gap-x-1'>
        {#if pair.broods}
            {#each pair.broods as brood, index }
                <Brood {index} {brood} {layer} nolabel={index>0} bind:invalid={invalids[index]} {disabled}/>
            {/each}
        {/if}
    </div>

</fieldset>

<style>

</style>