<script>
    import InputRing from '../input/InputRing.svelte';

    export let parents=[];
    export let disabled = true;

    const sexes = [ '1.0', '0.1' ];

    function onAddParent() {
        parents.push( {sex:'0.1', ring:{ country:'D', year:new Date().getFullYear()-1, code:null }, score: null, parents: { id:null, breeder:null, year:2021, name:null } } );
        parents = parents; // trigger, also to keep pair happy
    }
</script>

<div class='w-full flex flex-col p-2 gap-1'>
    <h4>Elterntiere <span class='clickable' on:click={onAddParent}>[+]</span></h4>

    <div class='flex flex-row gap-x-2 text-xs text-gray-600'>
        <div class='w-12 '><br>Geschlecht</div>
        <div class='w-10'><br>Land</div>
        <div class='w-10'><br>Jahr</div>
        <div class='w-16'><br>Nummer</div>
        <div class='w-10'><br>Ø Note</div>
        <div class='flex flex-col text-xs text-gray-600 whitespace-nowrap'>
            <div>Abstammung</div>
            <div class='flex flex-row gap-x-1'>
                <div class='w-16'>Züchter</div>
                <div class='w-8'>Jahr</div>
                <div class='w-16'>Stamm</div>
            </div>
        </div>
    </div>
    {#each parents as parent}
        <div class='flex flex-row gap-x-2 mb-1'>

            <select class='w-12 border border-b-red-600' bind:value={parent.sex} {disabled}>
                {#each sexes as sex} <option value={sex}>{sex}</option>{/each}
            </select>

            <InputRing class='border border-b-red-600' bind:value={parent.ring} {disabled}/>

            <input class='w-10 border border-b-red-600' type='number' min=90 max=97 maxlength=2 bind:value={parent.score} {disabled}>

            <div class='flex flex-row border border-b-red-600 gap-x-1' >
                <input class='w-16' type='number' min=0 max=99999 step=1 bind:value={parent.parents.breeder} {disabled}>
                <input class='w-8' bind:value={parent.year} {disabled}>
                <input class='w-16' type='text' maxlength=8 bind:value={parent.parents.name} {disabled}>
            </div>
        </div>
    {/each}
</div>