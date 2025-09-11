<script>
//    import { createEventDispatcher } from 'svelte';
    import store from '$lib/js/store.svelte.js';
    import { Select } from '$lib/cmp/form/Form.svelte';

//    export let year;
    let { group=$bindable(), section=$bindable(), year=$bindable() } = $props();

    let years = getYears();

    let groups = ['I', 'II', 'III'];

    function getYears() {
        const thisYear = new Date().getFullYear();
        const years = [];
        for( let year=thisYear; year>=1980; year-- ) {
            years.push( year );
        }
        return years;
    }

</script>



<div class='flex flex-row border border-gray-400 bg-gray-100 p-2 gap-x-4 justify-center'>
    <Select label="Sparte" bind:value={section} on:change={onSection} title='Sparte zum Eingeben'>
        {#each store.standard.rootSections as section}
            <option value={section}>{section.name}</option>
        {/each}
        <option value={store.aocSection}>{store.aocSection.name}</option>
    </Select>

    <Select label="Gruppe" bind:value={group} on:change={onGroup} disabled={section && section.id === 5 } title='Zuchtbuchgruppe I, II oder III'>
        {#each groups as group}
            <option value={group}>{group}</option>
        {/each}
    </Select>
</div>



<style>

</style>