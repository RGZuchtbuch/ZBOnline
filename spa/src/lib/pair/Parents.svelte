<script>
    import { router } from 'tinro';
    import validator from '../../js/validator.js';

    import InputDate from '../common/form/input/DateInput.svelte';
    import Parent from "./Parent.svelte";
    import FormStatus from '../common/form/Status.svelte';

    export let pair;
    export let disabled = false;
    export let invalid = false;

    const layer = pair.sectionId !== PIGEONS;

    //    let input;

    const validate = {
        date:       (v) => validator(v).date().orNull().isValid(),
    }

    function addParent( pairId ) {
//        if( ! pair.parents ) pair.parents = []; // needed ?
        const parent = { pairId:pairId, sex:pair.parents.length === 0 ? '1.0' : '0.1', ring:null, score:null, parentsPairId:null };
        pair.parents = [...pair.parents, parent ];
    }

    function setSiresAndDames( pair ) {
        let sires = 0;
        let dames = 0;
        if( pair.parents ) {
            for (const parent of pair.parents) {
                if (parent.ring) {
                    if (parent.sex === '1.0') { //sire
                        sires++;
                    } else {
                        dames++;
                    }
                }
            }
            pair.sires = sires;
            pair.dames = dames;
            pair = pair;
        }
    }

    function update( url, pair ) {
        while( pair.parents.length < 2 ) { // want at least 2
            addParent( pair.id )
        }
    }

    $: update( $router.url, pair );

    $: setSiresAndDames( pair );

</script>

<div class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow' class:invalid>Abstammung [{pair.sires}.{pair.dames}] {layer}</div>
        {#if layer}
            <button type='button' class='w-6 border rounded bg-header text-center text-white cursor-pointer' class:disabled title='Elterntier hinzufügen' on:click={addParent}>✚</button>
        {/if}
    </div>


    <div class='flex flex-col p-2 gap-1'>
        <div class='flex'>
            <InputDate class='w-28' label={'Angepaart am'} bind:value={pair.paired} validator={validate.date} />
            <div class='grow'></div>
            <FormStatus />
        </div>

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