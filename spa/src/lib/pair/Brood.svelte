<script>
    import {onMount} from "svelte";
    import validator from '../../js/validator.js';

    import { pct } from '../../js/util.js'

    import InputDate from '../common/form/input/DateInput.svelte';
    import InputNumber from '../common/form/input/NumberInput.svelte';
    import InputRing from '../common/form/input/RingInput.svelte';
    import InputText from '../common/form/input/TextInput.svelte';

    export let index;
    export let brood;
    export let pair;
    export let nolabel = false;

    const validate = {
        date:      (v) => validator(v).date().between( (pair.year-1)+'-12-01', (pair.year)+'-09-30' ).orNull().isValid(), // 1-10 → 30-09
        chicks:    (v) => validator(v).number().range( 0, 2 ).orNull().isValid(), // pigeons
        eggs:      (v) => validator(v).number().range( 1, 999 ).orNull().isValid(), // layers
        fertile:   (v) => validator(v).number().range( 0, brood.eggs ).orNull().isValid(), // layers
        hatched:   (v) => validator(v).number().range( 0, brood.fertile === null ? brood.eggs : brood.fertile ).orNullIf( brood.eggs === null ).isValid(), // layers
        ringed:    (v) => validator(v).date().if( brood.hatched > 0 ).between( brood.start, (pair.year)+'-09-30' ).orNull().isValid(), // 1-10 → 30-09
        ring1:     (v) => validator(v).ring().if( brood.hatched >= 1 ).orNull().isValid(),
        ring2:     (v) => validator(v).ring().if( brood.hatched >= 2 ).orNull().isValid(),
    }

    function getFertility( eggs, fertile ) {
        if( eggs && fertile ) {
            return pct(fertile, eggs, 0);
        }
        return null;
    }

    function getHatching( brood ) {
        if( pair.sectionId === PIGEONS ) { // layer
            return pct( brood.hatched, 2, 0 ); // defaults to 2 eggs
        } else { // pigeon
            return pct( brood.hatched, brood.eggs, 0 );
        }
    }

    onMount( () => {
    //    if( pair.sectionId === PIGEONS ) pair.eggs = 2
    })

</script>

<fieldset class='flex flex-row gap-x-1'>
    {#if pair.sectionId === PIGEONS } <!-- PIGEONS -->
        <div class='grow flex flex-row gap-x-1'>
            <InputText class='w-8' label={nolabel ? '' : '#'} value={index+1} disabled/>
            <InputDate class='w-28' label={nolabel ? '' : 'Gelegt am'} bind:value={brood.start} validator={validate.date} />
            <div class='w-2'></div>
            <InputNumber class='w-16' label={nolabel ? '' : 'Küken'} bind:value={brood.hatched} error='0 - 2'  validator={validate.chicks}/>
            <InputDate class='w-28 ml-4' label={nolabel ? '' : 'Beringt am'} bind:value={brood.ringed} validator={validate.ringed} />
            {#if brood.chicks && brood.chicks.length>=1 }
                <InputRing class='w-32' label={nolabel ? '' : 'Ring Küken #1'} bind:value={brood.chicks[0].ring} validator={validate.ring1} />
            {/if}
            {#if brood.chicks && brood.chicks.length>=2 }
                <InputRing class='w-32' label={nolabel ? '' : 'Ring Küken #2'} bind:value={brood.chicks[1].ring} validator={validate.ring2} />
            {/if}
        </div>
        <div class='flex flex-row gap-x-1'>
            <InputText class='w-16' label={nolabel ? '' : 'Schlupf'} value={getHatching( brood )} disabled />
        </div>
    {:else} <!-- Layers -->
        <div class='grow flex flex-row gap-x-1'>
            <InputText class='w-8' label={nolabel ? '' : '#'} value={index+1} disabled />
            <InputDate class='w-28' label={nolabel ? '' : 'Am'} bind:value={brood.start} validator={validate.date} />
            <div class='w-2'></div>
            <InputNumber class='w-16' label={nolabel ? '' : 'Eingelegt*'} bind:value={brood.eggs} validator={validate.eggs} />
            <InputNumber class='w-16' label={nolabel ? '' : 'Befruchtet'} bind:value={brood.fertile} error='Fehler' validator={validate.fertile} />
            <InputNumber class='w-16' label={nolabel ? '' : 'Geschlüpft*'} bind:value={brood.hatched} error='Fehler' validator={validate.hatched} />
            <InputDate class='w-28 ml-4' label={nolabel ? '' : 'Beringt am'} bind:value={brood.ringed} validator={validate.ringed} />
        </div>
        <div class='flex flex-row gap-x-1'>
            <InputText class='w-16' label={nolabel ? '' : 'Befruchtung'} value={ getFertility( brood.eggs, brood.fertile )} disabled />
            <InputText class='w-16' label={nolabel ? '' : 'Schlupf'} value={ getHatching( brood )} disabled />
        </div>

    {/if}

</fieldset>


<style>

</style>