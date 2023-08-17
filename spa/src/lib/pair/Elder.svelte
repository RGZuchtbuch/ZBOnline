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

    export let pair;
    export let elder;
    export let nolabel = false;
    export let disabled = true;

    let ancestorPairs = null


    function getEldersReportOptions( string ) {
        if( string ) {
            const ring = toRing(string);
            console.log('Ring', ring);

            api.breeder.pairsInYear.get( pair.breederId, ring.year).then(response => {
                console.log('Options', response);
                ancestorPairs = response.pairs;
            })
        }
    }

    function getResultsForElderPair( pairId ) {

    }

    function validate( elders ) {

    }

    $: getEldersReportOptions( elder.ring );
    $: getResultsForElderPair( elder.pair );

</script>


<div class='flex flex-row gap-x-1'>
    <div class='grow flex flex-row gap-x-1'>
        <Select class='w-16' label={nolabel ? '' : 'Sex'} bind:value={elder.sex} title='Hahn (1.0) oder Henne (0.1)' {disabled} required>
            {#each ['1.0', '0.1'] as sex }
                <option value={sex}>{sex}</option>
            {/each}
        </Select>
        <InputRing class='w-32' label={nolabel ? '' : 'Ring [D J Bs Nr]'} bind:value={elder.ring} title='Ring "D23 AZ 999"' {disabled}/>
        <InputNumber class='w-16' label={nolabel ? '' : '∅ Note'} bind:value={elder.score} min=89 max=97 step=0.1 {disabled} />

        <div class='w-8 self-center text-center'> ← </div>


        <Select class='w-32' label={nolabel ? '' : 'Aus Stamm / Paar'} bind:value={elder.pair} {disabled} >
            <option value={null}> ? </option>
            {#if ancestorPairs}
                {#each ancestorPairs as ancestorPair }
                    <option value={ancestorPair.id} > {ancestorPair.breederId+':'+ancestorPair.year+':'+ancestorPair.name} </option>
                {/each}
            {/if}
        </Select>

    </div>

    {#if elder.pair}
        <div class='flex flex-row gap-x-1'>
            <InputText class='grow' label={nolabel ? '' : 'Stamm Leistungen'} value='Todo 160 49, 90% 80%, 94.1' readonly disabled/>
        </div>
    {/if}
</div>



<style>

</style>