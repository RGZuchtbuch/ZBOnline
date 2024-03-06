<script>
    import api from '../../js/api.js';
    import {dec, pct, toRing} from '../../js/util.js';
    import InputButton from '../common/input/Button.svelte';
    import InputDate from '../common/input/Date.svelte';
    import InputNumber from '../common/input/Number.svelte';
    import InputRing from '../common/input/Ring.svelte';
    import InputText from '../common/input/Text.svelte';
    import ReadText from '../read/Text.svelte';
    import Select from '../common/input/Select.svelte';

    export let pair;
    export let breederId;
    export let elder;
    export let index;
    export let nolabel = false;
    export let disabled = true;
    export let invalid = false;

    let input;
    let aPairs = null
    let aResults = { eggs:null, weight:null, fertility:null, hatching:null, score:null };


    let invalids = { sex:false, ring:false, score:false, ancestors:false };

    function getPotentialAncestorPairs( string ) {
        if( string ) {
            const ring = toRing(string);
            if( ring ) {
                api.breeder.pairsInYear.get( breederId, ring.year).then(response => {
                    aPairs = response.pairs;
                })
            }
        }
    }

    function update() {
        if( input !== elder ) { // to avoid cycles
            input = elder;
        }
    }

    function validate() {
        invalid = false;
        for( let key in invalids ) {
            invalid |= invalids[key];
        }
        invalid = Boolean( invalid );
        elder = input;
    }
    function updateAncestorResults() {
        if (input.pair && aPairs) {
            for (let aPair of aPairs) {
                if (aPair.id === input.pair) { // selected
                    if( pair.sectionId === 5 ) {
                        aResults = { chicks:dec(aPair.broodHatched), show:dec(aPair.showScore, 1) };
                    } else {
                        aResults = { eggs:dec(aPair.layEggs), weight:dec(aPair.layWeight), fertility:pct(aPair.broodFertile, aPair.broodEggs), hatching:pct(aPair.broodHatched, aPair.broodEggs), score:dec(aPair.showScore, 1) }
                    }
                    return;
                }
            }
        }
    }

    $: getPotentialAncestorPairs( elder.ring );

    $: update( elder ); // should be before validate to ensure defined input
    $: validate( invalids );
    $: updateAncestorResults( aPairs )

</script>


<div class='flex flex-row gap-x-1' >
    <div class='grow flex flex-row gap-x-1'>
        <InputText class='w-8' label={nolabel ? '' : '#'} value={index+1} disabled readonly />
        <Select class='w-16' label={nolabel ? '' : 'Sex'} bind:value={input.sex} title='Hahn (1.0) oder Henne (0.1)' {disabled} required>
            {#each ['1.0', '0.1'] as sex }
                <option value={sex}>{sex}</option>
            {/each}
        </Select>
        <InputRing class='w-32' label={nolabel ? '' : 'Ring [D J Bs Nr]'} bind:value={input.ring} bind:invalid={invalids.ring} {disabled}/>
        <InputNumber class='w-16' label={nolabel ? '' : '∅ Note'} bind:value={input.score} min=89 max=97 step=0.1 bind:invalid={invalids.score} {disabled} />

        <div class='w-8 self-center text-center'> ← </div>

        <Select class='w-32' label={nolabel ? '' : 'Aus Stamm / Paar'} bind:value={input.pair} {disabled} >
            <option value={null}> ? </option>
            {#if aPairs}
                {#each aPairs as aPair }
                    <option value={aPair.id} selected={aPair.id === input.pair} > {aPair.id} {aPair.breederId+':'+aPair.year+':'+aPair.name} </option>
                {/each}
            {/if}
        </Select>
    </div>

    {#if aResults }
        {#if pair.sectionId == 5 }
            <div class='flex flex-row gap-x-1'>
                <div class='w-16'></div>
                <div class='w-16'></div>
                <div class='w-16'></div>
                <InputText class='w-16' label={nolabel ? '' : 'Küken'} value={aResults.hatching} readonly disabled/>
                <InputText class='w-16' label={nolabel ? '' : '∅ Note'} value={aResults.score} readonly disabled/>
            </div>
        {:else}
            <div class='flex flex-row gap-x-1'>
                <InputText class='w-16' label={nolabel ? '' : '# Eier /J'} value={aResults.eggs} readonly disabled/>
                <InputText class='w-16' label={nolabel ? '' : '∅ Gewicht'} value={aResults.weight} readonly disabled/>
                <InputText class='w-16' label={nolabel ? '' : '% Befruchtet'} value={aResults.fertility} readonly disabled/>
                <InputText class='w-16' label={nolabel ? '' : '% Geschlüpft'} value={aResults.hatching} readonly disabled/>
                <InputText class='w-16' label={nolabel ? '' : '∅ Note'} value={aResults.score} readonly disabled/>
            </div>
        {/if}
    {/if}
</div>



<style>

</style>