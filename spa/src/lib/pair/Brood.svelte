<script>
    import {onMount} from "svelte";
    import { pct, validator } from '../../js/util.js'

    import InputButton from '../common/input/Button.svelte';
    import InputDate from '../common/input/Date.svelte';
    import InputNumber from '../common/input/Number.svelte';
    import InputRing from '../common/input/Ring.svelte';
    import InputText from '../common/input/Text.svelte';
    import Select from '../common/input/Select.svelte';

    export let index;
    export let brood;
    export let nolabel = false;
    export let layer = true; // type of brood depends on this
    export let disabled;
    export let invalid = false;


    function init() {
    }


    function getFertility( eggs, fertile ) {
        if( eggs && fertile ) {
            return pct(fertile, eggs, 0);
        }
        return null;
    }

    function getHatching( brood ) {
        if( layer ) {
            return pct( brood.hatched, brood.eggs, 0 );
        } else {
            return pct( brood.hatched, 2, 0 ); // defaults to 2 eggs
        }
    }

    function onInput( event ) {
        validate();
    }

    function validate() {
        invalid = false ||
            validator( brood.start ).date().nullable().isInvalid() ||
            validator( brood.ringed ).date().nullable().isInvalid();
        if( layer ) {
            invalid |=
                validator( brood.eggs ).number().range( 0, 99999 ).nullable().isInvalid() ||
                validator( brood.fertile ).number().range( 0, brood.eggs ).nullable().isInvalid() ||
                validator( brood.hatched ).number().range( 0, brood.fertile ? brood.fertile : brood.eggs ).nullable().isInvalid();
        } else {
            invalid |=
                validator( brood.hatched ).number().range( 0, 2 ).nullable().isInvalid();
            for( const chick of brood.chicks ) {
                invalid ||= validator( chick.ring ).ring().nullable().isInvalid();
            }
        }
        console.log("Validate", brood.ringed, invalid, validator( brood.ringed ).date().isInvalid(), validator( brood.ringed ).date().nullable().isInvalid() );
    }

    onMount( validate );

</script>

<fieldset class='flex flex-row gap-x-1' on:input={onInput}>
    {#if layer }
        <div class='grow flex flex-row gap-x-1'>
            <InputText class='w-8' label={nolabel ? '' : '#'} value={index+1} disabled readonly />
            <InputDate class='w-24' label={nolabel ? '' : 'Am'} bind:value={brood.start} />
            <InputNumber class='w-16' label={nolabel ? '' : 'Eingelegt*'} bind:value={brood.eggs} min=1 max={99999}/>
            <InputNumber class='w-16' label={nolabel ? '' : 'Befruchtet'} bind:value={brood.fertile} min=0 max={brood.eggs} error={0+' - '+brood.eggs} />
            <InputNumber class='w-16' label={nolabel ? '' : 'Geschlüpft*'} bind:value={brood.hatched} min=0 max={brood.fertile ? brood.fertile : brood.eggs} error={0+' - '+brood.fertile} />
            <InputDate class='w-20' label={nolabel ? '' : 'Beringt am'} bind:value={brood.ringed} disabled={disabled || brood.hatched===0} />
        </div>
        <div class='flex flex-row gap-x-1'>
            <InputText class='w-16' label={nolabel ? '' : 'Befruchtung'} value={ getFertility( brood.eggs, brood.fertile )} disabled readonly />
            <InputText class='w-16' label={nolabel ? '' : 'Schlupf'} value={getHatching( brood )} disabled readonly />
        </div>

    {:else}

        <div class='grow flex flex-row gap-x-1'>
            <InputNumber class='w-8' label={nolabel ? '' : '#'} value={index+1} disabled readonly />
            <InputDate class='w-20' label={nolabel ? '' : 'Gelegt am'} bind:value={brood.start} />
            <InputNumber class='w-20' label={nolabel ? '' : 'Küken'} bind:value={brood.hatched} min=0 max=2 error='0 - 2' />
            <InputDate class='w-20' label={nolabel ? '' : 'Beringt am'} bind:value={brood.ringed} disabled={brood.hatched<=0} />
            {#if brood.chicks}
                <InputRing class='32' label={nolabel ? '' : 'Ring Küken #1'} bind:value={brood.chicks[0].ring} disabled={brood.hatched<1}/>
                <InputRing class='32' label={nolabel ? '' : 'Ring Küken #2'} bind:value={brood.chicks[1].ring} disabled={brood.hatched<2}/>
            {/if}
        </div>
        <div class='flex flex-row gap-x-1'>
            <InputText class='w-16' label={nolabel ? '' : 'Schlupf'} value={getHatching( brood )} disabled readonly />
        </div>
    {/if}

</fieldset>


<style>

</style>