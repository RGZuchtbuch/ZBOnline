<script>
    import api from '../../js/api.js';
    import {dec, pct, toRing} from '../../js/util.js';
    import validator from '../../js/validator.js';

    import InputNumber from '../common/form/input/NumberInput.svelte';
    import InputRing from '../common/form/input/RingInput.svelte';
    import InputText from '../common/form/input/TextInput.svelte';
    import Select from '../common/form/input/Select.svelte';

    //export let pair;
    export let index;
    export let parent; // target
    export let pair;
    export let nolabel = false; // for list of

    let ringObject = null; // to only load parentsPairs if year changed
    let ancestorPairs = null; // optional parent pairs
    let parentsPairResults = { eggs:null, weight:null, fertility:null, hatching:null, score:null }; // and their results


    const validate = {
        date:       (v) => validator(v).date().isValid(),
        ring:       (v) => validator(v).ring().orNull().isValid(),
        points:     (v) => validator(v).number().range( 89.0, 97.0 ).orNull().isValid(),
    }

    function getPotentialAncestorPairs( ring ) {
        let newRingObject = toRing( ring ); // decode input
        if( newRingObject && ( ! ringObject || ringObject.year !== newRingObject.year ) ) { // first time or valid ring and changed
            ringObject = newRingObject;
            api.breeder.year.pairs.get(pair.breederId, ringObject.year).then(response => {
                ancestorPairs = response.pairs;
            })
        }
    }

    $: getPotentialAncestorPairs( parent.ring );

</script>


<div class='flex flex-row gap-x-1' >
    <div class='grow flex flex-row gap-x-1'>
        <InputText class='w-8' label={nolabel ? '' : '#'} value={index+1} disabled />
        <Select class='w-16' label={nolabel ? '' : 'Sex'} bind:value={parent.sex} title='Hahn (1.0) oder Henne (0.1)' >
            {#each ['1.0', '0.1'] as sex }
                <option value={sex}>{sex}</option>
            {/each}
        </Select>
        <InputRing class='w-32' label={nolabel ? '' : 'Ring [D J Bs Nr]'} bind:value={parent.ring} validator={validate.ring}/>
        <InputNumber class='w-16' label={nolabel ? '' : '∅ Note'} bind:value={parent.score} step={0.1} validator={validate.points}/>

        <div class='w-8 self-center text-center'> ← </div>

        <Select class='w-32' label={nolabel ? '' : 'Aus Stamm / Paar'} bind:value={parent.parentsPairId}>
            <option value={null}> ? </option>
            {#if ancestorPairs}
                {#each ancestorPairs as ancestorPair }
                    <option value={ancestorPair.id} selected={ancestorPair.id === parent.parentsPairId} > {ancestorPair.year+':'+ancestorPair.name} </option>
                {/each}
            {/if}
        </Select>
    </div>

    {#if parentsPairResults }
        {#if pair.sectionId === 5 }
            <div class='flex flex-row gap-x-1'>
                <div class='w-16'></div>
                <div class='w-16'></div>
                <div class='w-16'></div>
                <InputText class='w-16' label={nolabel ? '' : 'Küken'} value={parentsPairResults.hatching} readonly disabled/>
                <InputText class='w-16' label={nolabel ? '' : '∅ Note'} value={parentsPairResults.score} readonly disabled/>
            </div>
        {:else}
            <div class='flex flex-row gap-x-1'>
                <InputText class='w-16' label={nolabel ? '' : '# Eier /J'} value={parentsPairResults.eggs} readonly disabled/>
                <InputText class='w-16' label={nolabel ? '' : '∅ Gewicht'} value={parentsPairResults.weight} readonly disabled/>
                <InputText class='w-16' label={nolabel ? '' : '% Befruchtet'} value={parentsPairResults.fertility} readonly disabled/>
                <InputText class='w-16' label={nolabel ? '' : '% Geschlüpft'} value={parentsPairResults.hatching} readonly disabled/>
                <InputText class='w-16' label={nolabel ? '' : '∅ Note'} value={parentsPairResults.score} readonly disabled/>
            </div>
        {/if}
    {/if}
</div>



<style>

</style>