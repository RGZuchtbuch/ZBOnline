<script>
    import { printPct } from '../../js/util.js'

    import InputButton from '../input/Button.svelte';
    import InputDate from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputRing from '../input/Ring.svelte';
    import InputText from '../input/Text.svelte';
    import Select from '../select/Select.svelte';

    export let layer = true; // type of brood depends on this
    export let broods;
    export let disabled;

    console.log( 'Broods', broods );

    function addBrood() {
        console.log( 'Add Brood' );
        let brood = ! layer ?
            { index:Date.now(), start:null, eggs:2, fertile:2, hatched:null, ringed:null, chicks:[ { index:0, ring:null }, { index:1, ring:null} ] } :
            { index:Date.now(), start:null, eggs:null, fertile:null, hatched:null, ringed:null, chicks:[] };

        broods = [...broods, brood];
    }

    function removeBrood( index ) {
        return () => {
            broods.splice(index, 1);
            broods = broods; // trigger
            console.log( 'Brood', index, broods, broods.length );
        }
    }

    function getFertility( eggs, fertile ) {
        if( eggs && fertile ) {
            return printPct(fertile / eggs);
        }
        return null;
    }

    function getHatching( eggs, hatched ) {
        if( eggs && hatched ) {
            return printPct(hatched / eggs);
        }
        return null;
    }


</script>

<div class='flex flex-col my-2'>
    <h4>Brutleistung</h4>

    <div class='flex flex-col gap-y-1'>
        {#if broods}
            {#each broods as brood, index }
                {#if layer }
                    <div class='flex flex-row gap-x-1'>
                        <div class='grow flex flex-row gap-x-1'>
                            <InputNumber class='w-8' label={index===0 ? '#' : null} value={index} readonly />
                            <InputDate class='w-24' label={index===0 ? 'Am' : null } bind:value={brood.start} {disabled} />
                            <InputNumber class='w-16' label={index===0 ? 'Eingelegt' : null } bind:value={brood.eggs} min=1 max={99999} {disabled} />
                            <InputNumber class='w-16' label={index===0 ? 'Befruchtet' : null } bind:value={brood.fertile} min=0 max={brood.eggs} error={0+' - '+brood.eggs} {disabled} />
                            <InputNumber class='w-16' label={index===0 ? 'Geschlüpft' : null } bind:value={brood.hatched} min=0 max={brood.fertile} error={0+' - '+brood.fertile} {disabled} />
                            <InputDate class='w-20' label={index===0 ? 'Beringt am' : null } bind:value={brood.ringed} disabled={disabled || brood.hatched<=0} />
                            <InputText class='32' label={index===0 ? 'Ringe Jungtiere' : null } {disabled}/>
                        </div>
                        <div class='flex flex-row gap-x-1'>
                            <InputText class='w-16' label={index===0 ? 'Befruchtung' : null } value={ getFertility( brood.eggs, brood.fertile )} readonly />
                            <InputText class='w-16' label={index===0 ? 'Schlupf' : null } value={getHatching( brood.eggs, brood.hatched )} readonly />
                            {#if index >= 1}
                                <InputButton class='w-8' on:click={removeBrood(index)} label={index===0 ? 'Entf' : null} value='X' readonly />
                            {:else}
                                <div class='w-8' />
                            {/if}
                        </div>
                    </div>
                {:else}
                    <div class='flex flex-row gap-x-1'>
                        <div class='grow flex flex-row gap-x-1'>
                            <InputNumber class='w-8' label={index==0 ? '#' : null} value={index} min=0 readonly />
                            <InputDate class='w-20' label={index===0 ? 'Gelegt am' : null } bind:value={brood.start} {disabled} />
                            <InputNumber class='w-10' label={index===0 ? 'Küken' : null } bind:value={brood.hatched} min=0 max={brood.fertile} error={0+' - '+brood.fertile} {disabled} />
                            <InputDate class='w-20' label={index===0 ? 'Beringt am' : null } bind:value={brood.ringed} disabled={disabled || brood.hatched<=0} />
                            <InputText class='32' label={index===0 ? 'Ringe Jungtiere' : null } {disabled}/>
                        </div>
                        <div class='flex flex-row gap-x-1'>
                            <InputText class='w-16' label={index===0 ? 'Schlupf' : null } value={getHatching( brood.eggs, brood.hatched )} readonly />
                            <InputButton class='w-8' on:click={removeBrood(index)} label={index===0 ? 'Entf' : null} value='X' readonly />
                        </div>
                    </div>
                {/if}

            {/each}
        {/if}
        <div class='rounded border bg-gray-500 text-center text-white cursor-pointer' on:click={addBrood}>Brut zufügen</div>
    </div>

</div>

<style>

</style>