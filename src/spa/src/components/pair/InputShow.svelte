<script>
    export let show;
    export let disabled = true;
</script>

<div class='w-full flex flex-col p-2 gap-2'>
    <h4>Ausstellungsbewertungen Jungtiere</h4>
    <div class='flex flex-row gap-2'>
        {#each Object.keys(show.scores) as key}
            <div class='flex-flex-col'>
                <div class='text-xs text-gray-600'>{#if key=='89'}U/O{:else} {key} {/if}</div>
                <input class='w-12' type='number' min=0 max=9999 step=1 bind:value={ show.scores[key] } {disabled}>
            </div>
        {/each}
    </div>
</div>