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
    import Elder from "./Elder.svelte";

    export let pair;
    export let layer = true;
    export let disabled = false;
    export let invalid = false;

//    let elders = [];
    let input;
    let invalids = {}; // per pair.id

    function newElder() {
        return { sex:pair.elders.length === 0 ? '1.0' : '0.1', ring:null, score:null, pair:null }
    }

    function addElder() {
        if( ! input.elders ) pair.elders = [];
        input.elders = [...input.elders, newElder() ]; // index just for rendering

    }

    function setSiresAndDames() {
        input.sires = 0;
        input.dames = 0;
        if( input.elders ) {
            for (const elder of input.elders) {
                if (elder.ring) {
                    if (elder.sex === '1.0') { //sire
                        input.sires++;
                    } else {
                        input.dames++;
                    }
                }
            }
        }
    }

    function validate() { // input
        setSiresAndDames();
        invalid = false;
        for( let elder of input.elders ) {
            invalid |= elder.invalid;
        }
        pair = input;
    }

    function update() { // pair
        if( input !== pair) input = pair;
        while( ! pair.elders || pair.elders.length < 2 ) { // want at least 2
            addElder();
        }
    }

    $: update( pair );
    $: validate( input );

</script>

<div class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow' >Abstammung [{input.sires}.{input.dames}] {#if layer}Leger{/if} {invalid}</div>
        {#if layer}
            <div class='w-6 border rounded text-center text-white cursor-pointer' class:disabled on:click={addElder} title='Elterntier hinzufügen'>✚</div>
        {/if}
    </div>


    <div class='flex flex-col p-2 gap-1'>
        <InputDate class='w-24' label={'Angepaart am'} bind:value={input.paired} {disabled} />
        {#if input.elders}
            {#each input.elders as elder, index }
                <Elder breederId={input.breederId} bind:elder={elder} nolabel={index > 0} bind:invalid={elder.invalid} {disabled}/>
            {/each}
        {/if}
    </div>

</div>



<style>

</style>