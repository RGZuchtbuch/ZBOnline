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

    //export let pair;
    export let breederId;
    export let elder;
    export let nolabel = false;
    export let disabled = true;
    export let invalid = false;

    let input;
    let ancestorPairs = null

    let invalids = { sex:false, ring:false, score:false, ancestors:false };

    function getEldersReportOptions( string ) {
        if( string ) {
            const ring = toRing(string);
            if( ring ) {
                api.breeder.pairsInYear.get( breederId, ring.year).then(response => {
                    ancestorPairs = response.pairs;
                })
            }
        }
    }

    function getResultsForElderPair( pairId ) {
        // TODO
    }

    function update() {
        if( input !== elder ) { // to avoid cycles
            input = elder;
        }
    }

    function validate() {
        console.log('Validate Elder input', input );

        invalid = false;
        for( let key in invalids ) {
            invalid |= invalids[key];
        }
        elder = input;
    }

    $: getEldersReportOptions( elder.ring );
    $: getResultsForElderPair( elder.pair );

    $: update( elder ); // should be before validate to ensure defined input
    $: validate( input  );

console.log( 'BI', breederId );
</script>

{invalid} : {invalids.ring }
<div class='flex flex-row gap-x-1' >
    <div class='grow flex flex-row gap-x-1'>
        <Select class='w-16' label={nolabel ? '' : 'Sex'} bind:value={input.sex} title='Hahn (1.0) oder Henne (0.1)' {disabled} required>
            {#each ['1.0', '0.1'] as sex }
                <option value={sex}>{sex}</option>
            {/each}
        </Select>
        <InputRing class='w-32' label={nolabel ? '' : 'Ring [D J Bs Nr]'} bind:value={input.ring} bind:invalid={invalids.ring} {disabled}/>
        <InputNumber class='w-16' label={nolabel ? '' : '∅ Note'} bind:value={input.score} min=89 max=97 step=0.1 {disabled} />

        <div class='w-8 self-center text-center'> ← </div>


        <Select class='w-32' label={nolabel ? '' : 'Aus Stamm / Paar'} bind:value={input.pair} {disabled} >
            <option value={null}> ? </option>
            {#if ancestorPairs}
                {#each ancestorPairs as ancestorPair }
                    <option value={ancestorPair.id} > {ancestorPair.breederId+':'+ancestorPair.year+':'+ancestorPair.name} </option>
                {/each}
            {/if}
        </Select>

    </div>

    {#if input.pair}
        <div class='flex flex-row gap-x-1'>
            <InputText class='grow' label={nolabel ? '' : 'Stamm Leistungen'} value='Todo 160 49, 90% 80%, 94.1' readonly disabled/>
        </div>
    {/if}
</div>



<style>

</style>