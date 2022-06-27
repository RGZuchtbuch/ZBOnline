<script>
    export let pair
    export let disabled = true;

    let groups = [ { id:1, name:'I'}, { id:1, name:'II'}, { id:1, name:'III'} ];

</script>

<div class='w-full flex flex-col p-2 gap-2'>
    <h4>Stamm [{pair.id})</h4>
    <div class='w-full flex flex-row gap-2'>

        <div class='flex flex-col w-64 border border-b-red-600'>
            <div class='text-xs text-gray-600'>Züchter</div>
            <div class=''>{pair.breeder.name} [{pair.breeder.id}]</div>
        </div>
        <div class='flex flex-col border border-b-red-600'>
            <div class='text-xs text-gray-600'>Jahr</div>
            <input class='w-16' type='number' min=1900 max=2099 step=1 bind:value={pair.year} {disabled}>
        </div>
        <div class='flex flex-col border border-b-red-600'>
            <div class='text-xs text-gray-600'>Nummer</div>
            <input class='w-16' type='text' maxlength=8 bind:value={pair.name} {disabled}>
        </div>
        <div class='flex flex-col border border-b-red-600'>
            <div class='text-xs text-gray-600'>Gruppe</div>
            <select class='w-12' bind:value={pair.group} {disabled}>
                {#each groups as group} <option value={group.id}>{group.name}</option>{/each}
            </select>
        </div>
        <div class='flex flex-col border border-b-red-600'>
            <div class='text-xs text-gray-600'>Anpaarung</div>
            <input class='w-32' type='date' bind:value={pair.paired} {disabled}>
        </div>
    </div>
</div>