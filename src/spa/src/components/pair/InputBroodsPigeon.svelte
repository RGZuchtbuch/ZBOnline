<script>
    import InputRing from '../input/InputRing.svelte';

    export let broods;
    export let disabled = true;

    function onAddBrood() {
        broods.push( {start: null, eggs: 2, fertile: 2, hatched: null, chicks:[ { country:'D', year:22, name:null}, { country:'D', year:22, name:null} ]} );
        broods = broods;
    }
</script>



<div class='w-full flex flex-col p-2 gap-2'>
    <h4>Tauben Bruten <span class='clickable' on:click={onAddBrood}>[+]</span></h4>
    <div class='flex flex-row gap-x-2 text-xs text-gray-600'>
        <div class='w-32'>Gelegt</div>
        <div class='w-8'>Geschlüpft</div>
        <div class='w-32'>Beringt</div>
        <div class='w-16'>Küken 1</div>
        <div class='w-16'>Küken 2</div>
    </div>
    <div class='flex flex-col gap-1' >
        {#each broods as brood}
            <div class='flex flex-row gap-x-2 mb-1'>
                <input class='w-32' type='date' bind:value={brood.start} {disabled}>
                <input class='w-8' type='number' min=0 max=9999 step=1 bind:value={brood.eggs} {disabled}>
                <input class='w-32' type='date' bind:value={brood.ringed} {disabled}>
                {#each brood.chicks as ring}
                    <InputRing class='border border-b-red-600' bind:value={ring} {disabled}/>
                {/each}
            </div>
        {/each}
    </div>
</div>