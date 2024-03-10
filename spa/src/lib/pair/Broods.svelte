<script>
    import {onMount} from "svelte";
    import Brood from "./Brood.svelte";

    export let pair;

//    let layer;

    function addBrood( pairId ) {
        const brood = { pairId:pairId, start:null, eggs:null, fertile:null, hatched:null, ringed:null, chicks:[ newChick( pairId, 0 ), newChick( pairId, 0 ) ] };
        pair.broods = [ ...pair.broods, brood ];
    }

    function newChick( pairId, broodId ) {
        return {pairId:pairId, broodId:broodId, ring:null};
    }

    function update( pair ) {
        while( pair.broods.length < 2 ) { // want at least 2
            addBrood( pair.id )
        }
    }

    $: update( pair );

</script>


<fieldset class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Brutleistung</div>
        <button type='button' class='w-6 border rounded bg-header text-center text-white cursor-pointer' title='Brut/Nest hinzufügen' on:click={addBrood}>✚</button>
    </div>

    <div class='flex flex-col p-2 gap-x-1'>
        {#if pair.broods}
            {#each pair.broods as brood, index }
                <Brood bind:brood={brood} {index} {pair} nolabel={index>0}/>
            {/each}
        {/if}
    </div>

</fieldset>

<style>
    .invalid {
        @apply text-red-800;
    }
</style>