<script>
    import { printPct } from '../../js/util.js'

    import InputButton from '../input/Button.svelte';
    import InputDate from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputRing from '../input/Ring.svelte';
    import InputText from '../input/Text.svelte';
    import Select from '../select/Select.svelte';

    export let sectionId; // type of brood depends on this
    export let broods;
    export let disabled;

    function addBrood() {
        console.log( 'Add Brood' );
        let brood = ( sectionId === 5 ) ?
            { index:Date.now(), start:null, eggs:2, fertile:2, hatched:null, ringed:null, chicks:[ { index:0, ring:null }, { index:1, ring:null} ] } :
            { index:Date.now(), start:null, eggs:null, fertile:null, hatched:null, ringed:null, chicks:[] };

        broods = [...broods, brood];
    }

    function removeBrood( index ) {
        return () => {
            console.log( 'Brood', index, broods );
            broods.splice(index, 1);
            broods = broods; // trigger
            console.log( 'Brood', index, broods );
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
    <div>Brutleistung</div>

    <div class='flex flex-col gap-y-1'>
        {#if broods}
            { sectionId }
            {#each broods as brood, i (brood.index) }
                {#if sectionId == 5 }
                    <div class='flex flex-row gap-x-1'>
                        <div class='grow flex flex-row gap-x-1'>
                            <InputNumber class='w-8' label={i===0 ? '#' : null} value={i} readonly />
                            <InputDate class='w-20' label={i===0 ? 'Gelegt am' : null } bind:value={brood.start} {disabled} />
                            <InputNumber class='w-10' label={i===0 ? 'Küken' : null } bind:value={brood.hatched} min=0 max={brood.fertile} error={0+' - '+brood.fertile} {disabled} />
                            <InputDate class='w-20' label={i===0 ? 'Beringt am' : null } bind:value={brood.ringed} disabled={disabled || brood.hatched<=0} />
                            {#each brood.chicks as chick, c (chick.index)}
                                <InputRing class='w-32' label={i===0 ? 'Ring #'+(i+1) : null } bind:value={chick.ring} disabled={disabled || c>=brood.hatched} />
                            {/each}
                        </div>
                        <div class='flex flex-row gap-x-1'>
                            <InputText class='w-16' label={i===0 ? 'Schlupf' : null } value={getHatching( brood.eggs, brood.hatched )} readonly />
                            <InputButton class='w-8' on:click={removeBrood(i)} label={i===0 ? 'Entf' : null} value='X' readonly />
                        </div>
                    </div>
                {:else}
                    <div class='flex flex-row gap-x-1'>
                        <div class='grow flex flex-row gap-x-1'>
                            <InputNumber class='w-8' label={i===0 ? '#' : null} value={i} readonly />
                            <InputDate class='w-24' label={i===0 ? 'Am' : null } bind:value={brood.start} {disabled} />
                            <InputNumber class='w-16' label={i===0 ? 'Eingelegt' : null } bind:value={brood.eggs} min=1 max={99999} {disabled} />
                            <InputNumber class='w-16' label={i===0 ? 'Befruchtet' : null } bind:value={brood.fertile} min=0 max={brood.eggs} error={0+' - '+brood.eggs} {disabled} />
                            <InputNumber class='w-16' label={i===0 ? 'Geschlüpft' : null } bind:value={brood.hatched} min=0 max={brood.fertile} error={0+' - '+brood.fertile} {disabled} />
                        </div>
                        <div class='flex flex-row gap-x-1'>
                            <InputText class='w-16' label={i===0 ? 'Befruchtung' : null } value={ getFertility( brood.eggs, brood.fertile )} readonly />
                            <InputText class='w-16' label={i===0 ? 'Schlupf' : null } value={getHatching( brood.eggs, brood.hatched )} readonly />
                            <InputButton class='w-8' on:click={removeBrood(i)} label={i===0 ? 'Entf' : null} value='X' readonly />
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