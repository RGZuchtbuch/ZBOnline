<script>
    import {onMount} from "svelte";
    import Brood from "./Brood.svelte";
    import InputDate from '../common/form/input/DateInput.svelte';
    import FormStatus from '../common/form/Status.svelte';

    export let pair;

//    let layer;

    function addBrood() { // from form
        pair.broods = [ ...pair.broods, newBrood() ]; // push and trigger
    }

    function newBrood() {
        let brood = null;
        if( pair.sectionId === PIGEONS ) {
            brood = { pairId:pair.id, start:null, eggs:2, fertile:null, hatched:null, ringed:null, chicks:[ newChick(), newChick() ] };
        } else {
            brood = { pairId:pair.id, start:null, eggs:null, fertile:null, hatched:null, ringed:null };
        }
        return brood;
//        pair.broods = [ ...pair.broods, brood ];
    }

    function newChick() {
        return {pairId:pair.id, broodId:null, ring:null};
    }

    function update( pair ) {
        //while( pair.broods.length < 2 ) { // want at least 2
       //     addBrood( pair.id )
        //}

        const broods = pair.broods;
        for( let broodIndex=0; broodIndex<2; broodIndex++ ) { // add for at least 2 broods
            if( ! broods[ broodIndex ] ) {
                broods.push( newBrood() );
            }
            if( pair.sectionId === PIGEONS ) { // add chicks if not room for 2
                const chicks = broods[ broodIndex ].chicks;
                for( let chickIndex=0; chickIndex<2; chickIndex++ ) { // add fields for max 2 chicks
                    if( ! chicks[ chickIndex ] ) {
                        chicks.push( newChick() );
                    }
                }
            }
        }
    }

    $: update( pair );

</script>


<fieldset class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Brutleistung</div>
        <button type='button' class='w-6 border rounded bg-header text-center text-white cursor-pointer' title='Brut/Nest hinzufügen' on:click={addBrood}>✚</button>
    </div>

    <div class='flex justify-end'>
        <FormStatus />
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