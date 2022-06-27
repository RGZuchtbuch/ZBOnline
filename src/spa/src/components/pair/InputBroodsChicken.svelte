<script>
    export let broods;
    export let disabled = true;


    function onAddBrood() {
        broods.push( {start: null, eggs: null, fertile: null, hatched: null} );
        broods = broods;
    }
</script>


<div class='w-full flex flex-col p-2 gap-2'>
    <h4>Bruten <span class='clickable' on:click={onAddBrood}>[+]</span></h4>
    <div class='flex flex-row gap-x-2 text-xs text-gray-600'>
        <div class='w-32'>Start Datum</div>
        <div class='w-16'>Eier</div>
        <div class='w-16'>Befruchtet</div>
        <div class='w-16'>Geschlüpft</div>
    </div>
    <div class='flex flex-col gap-1' >
        {#each broods as brood}
            <div class='flex flex-row gap-x-2 mb-1'>
                <input class='w-32' type='date' bind:value={brood.start} {disabled}>
                <input class='w-16' type='number' min=0 max=9999 step=1 bind:value={brood.eggs} {disabled}>
                <input class='w-16' type='number' min=0 max={brood.eggs} step=1 bind:value={brood.fertile} {disabled}>
                <input class='w-16' type='number' min=0 max={brood.fertile} step=1 bind:value={brood.hatched} {disabled}>
            </div>
        {/each}
    </div>
</div>