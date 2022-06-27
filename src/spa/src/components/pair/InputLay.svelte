<script>
    export let lay;
    export let disabled=true


</script>


<div class='w-full flex flex-col p-2 gap-2'>
    <h4>Gesammelte Eier</h4>
    <div class='flex flex-row gap-2'>
        <div class='border border-b-red-600'>
            <div class='text-xs text-gray-600'>Start Datum</div>
            <input class='w-32' type='date' bind:value={lay.start} {disabled}>
        </div>
        <div class='border border-b-red-600'>
            <div class='text-xs text-gray-600'>Bis (inkl) Datum</div>
            <input class='w-32' type='date' bind:value={lay.until} {disabled}>
        </div>
        <div class='border border-b-red-600'>
            <div class='text-xs text-gray-600'>Eier</div>
            <input class='w-16' type='number' min=0 max=365 step=1 bind:value={lay.eggs} {disabled}>
        </div>
    </div>
</div>