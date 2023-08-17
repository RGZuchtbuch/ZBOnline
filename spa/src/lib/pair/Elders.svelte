<script>
    import api from '../../js/api.js';
    import {toRing} from '../../js/util.js';
    import InputButton from '../common/input/Button.svelte';
    import InputDate from '../common/input/Date.svelte';
    import InputNumber from '../common/input/Number.svelte';
    import InputRing from '../common/input/Ring.svelte';
    import InputText from '../common/input/Text.svelte';
    import ReadText from '../read/Text.svelte';
    import Select from '../common/input/Select.svelte';
    import Parent from "./Elder.svelte";

    export let pair;
    export let layer = true;
    export let disabled = false;

    function newElder() {
        return { sex:pair.elders.length === 0 ? '1.0' : '0.1', ring:null, score:null, pair:null }
    }

    function addElder() {
        if( ! pair.elders ) pair.elders = [];
        pair.elders = [...pair.elders, newElder() ]; // index just for rendering
        pair = pair;
    }

    function getComposition( elders ) {
        let sires = 0;
        let dames = 0;
        if( elders ) {
            for (const elder of elders) {
                if (elder.ring) {
                    if (elder.sex === '1.0') { //sire
                        sires++;
                    } else {
                        dames++;
                    }
                }
            }
        }
        return ''+sires+"."+dames;
    }

    function update( pair ) {
        while( ! pair.elders || pair.elders.length < 2 ) { // want at least 2
            addElder();
        }
        //validate();
    }


// breederId={report.breederId} bind:paired={report.paired} bind:parents={report.parents} {layer}

    $: update( pair );

</script>

<div class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow' >Abstammung [{getComposition( pair.elders )}] {#if layer}Leger{/if} </div>
        {#if layer}
            <div class='w-6 border rounded text-center text-white cursor-pointer' class:disabled on:click={addElder} title='Elterntier hinzufügen'>✚</div>
        {/if}
    </div>


    <div class='flex flex-col p-2 gap-1'>
        <InputDate class='w-24' label={'Angepaart am'} bind:value={pair.paired} {disabled} />
        {#if pair.elders}
            {#each pair.elders as elder, index }
                <Parent {pair} bind:elder={elder} nolabel={index > 0} {disabled}/>
            {/each}
        {/if}
    </div>

</div>



<style>

</style>