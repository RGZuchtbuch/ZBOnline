<script>
    import validator from '../../js/validator.js';

    import InputDate from '../common/form/input/DateInput.svelte';
    import Parent from "./Parent.svelte";

    export let pair;
    export let layer = true;
    export let disabled = false;
    export let invalid = false;

//    let input;

    const validate = {
        date:       (v) => validator(v).date().orNull().isValid(),
    }

    function addParent() {
//        if( ! pair.parents ) pair.parents = []; // needed ?
        pair.parents = [...pair.parents, { pairId:pair.id, sex:pair.parents.length === 0 ? '1.0' : '0.1', ring:null, score:null, parentsPairId:null } ];
    }

    function setSiresAndDames( pair ) {
        pair.sires = 0;
        pair.dames = 0;
        if( pair.parents ) {
            for (const parent of pair.parents) {
                if (parent.ring) {
                    if (parent.sex === '1.0') { //sire
                        pair.sires++;
                    } else {
                        pair.dames++;
                    }
                }
            }
        }
    }

    $: setSiresAndDames( pair );

</script>

<div class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow' class:invalid>Abstammung [{pair.sires}.{pair.dames}]</div>
        {#if layer}
            <button type='button' class='w-6 border rounded bg-header text-center text-white cursor-pointer' class:disabled title='Elterntier hinzufügen' on:click={addParent}>✚</button>
        {/if}
    </div>


    <div class='flex flex-col p-2 gap-1'>
        <InputDate class='w-24' label={'Angepaart am'} bind:value={pair.paired} validator={validate.date} />
        {#if pair.parents}
            {#each pair.parents as parent, index }
                <Parent bind:parent={parent} {index} pair={pair} nolabel={index > 0}/>
            {/each}
        {/if}
    </div>

</div>



<style>
    .invalid {
        @apply text-red-800;
    }
</style>