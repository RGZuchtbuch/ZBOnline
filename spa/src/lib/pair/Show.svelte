<script>
    import {onMount} from "svelte";
    import dic from '../../js/dictionairy.js';
    import validator from '../../js/validator.js';

    import InputNumber from '../common/form/input/NumberInput.svelte';

    export let pair;

    let count = 0;
    let avgScore = null;

    const validate = {
        count:      v => validator(v).number().range( 0, 999 ).orNull().isValid(),
    }


    function init() {
        if( ! pair.show ) {
            pair.show = { 89:null, 90:null, 91:null, 92:null, 93:null, 94:null, 95:null, 96:null, 97:null };
        }
    }

    function update( pair ) {

        count = 0; // count nr of scores
        let total = 0; // for scores * points
        let keys = [89, 90, 91, 92, 93, 94, 95, 96, 97]; // only need these keys from show !
        for (let key of keys) {
            let value = pair.show[key];
            if (value) { // not null or 0
                count += value; // score can be null;
                total += value * key; // add tot total points
            } else {
                //pair.show[key] = null; //no value or 0
            }
        }
        avgScore = count > 0 ? total / count : null; // to average score
        pair = pair; // rerender
    }


    onMount( init )
    //$: update( pair );
    $: update( pair );

</script>

<fieldset class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Schauleistung <small class='align-top' title={dic.info.pair.show}>&#9432;</small></div>
        <div class='w-6'></div>
    </div>

    <div class='grow flex flex-row p-2 gap-x-1'>
        {#if pair.show}
            <InputNumber class='w-16 pr-2' label={'U/O'} bind:value={pair.show['89']} validator={validate.count} />
            <InputNumber class='w-16' label={'90'} bind:value={pair.show['90']} validator={validate.count} />
            <InputNumber class='w-16' label={'91'} bind:value={pair.show['91']} validator={validate.count} />
            <InputNumber class='w-16' label={'92'} bind:value={pair.show['92']} validator={validate.count} />
            <InputNumber class='w-16' label={'93'} bind:value={pair.show['93']} validator={validate.count} />
            <InputNumber class='w-16' label={'94'} bind:value={pair.show['94']} validator={validate.count} />
            <InputNumber class='w-16' label={'95'} bind:value={pair.show['95']} validator={validate.count} />
            <InputNumber class='w-16' label={'96'} bind:value={pair.show['96']} validator={validate.count} />
            <InputNumber class='w-16' label={'97'} bind:value={pair.show['97']} validator={validate.count} />
            <div class='grow'></div>
            <InputNumber class='w-16' label={'# Tiere'} value={ count } disabled />
            <InputNumber class='w-16' label={'∅ Note'} value={ avgScore ? avgScore.toFixed(1) : null } disabled />
        {/if}
    </div>
</fieldset>

<style>
    .invalid {
        @apply text-red-800;
    }
</style>