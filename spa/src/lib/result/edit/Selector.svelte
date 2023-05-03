<script>
    import { createEventDispatcher } from 'svelte';
    import {router} from "tinro";
    import Select from '../../common/input/Select.svelte';

    export let year;
    export let sectionId;
    export let group;

    const dispatch = createEventDispatcher();

    let years = getYears();
    let sections = [ // for selects
        {id: 3, name: 'Groß & Wassergeflügel'},
        {id: 11, name: 'Hühner (Groß)'}, { id: 12, name: 'Zwerghühner' }, {id: 13, name: 'Wachteln'},
        {id: 5, name: 'Tauben'},
        {id: 6, name: 'Ziergeflügel'}
    ];
    let groups = ['I', 'II', 'III'];

    function onSection( event ) {
        router.location.query.set( 'section', sectionId );
        if( sectionId === 5 ) {
            router.location.query.set( 'group', 'I' );
        }
    }
    function onYear( event ) {
        router.location.query.set( 'year', year );
    }
    function onGroup( event ) {
        router.location.query.set( 'group', group );
    }

    function onHelp() {
        dispatch( 'help', true );
    }

    function getYears() {
        const thisYear = new Date().getFullYear();
        const years = [];
        for( let year=thisYear; year>=2000; year-- ) {
            years.push( year );
        }
        return years;
    }

</script>



<div class='w-256 flex flex-row mx-2 rounded-t bg-header'>

    <div class='w-8'></div>
    <div class='grow flex gap-x-4 justify-center'>
        <Select label="Jahr" bind:value={year} on:change={onYear}>
            {#each years as year}
                <option value={year}>{year}</option>
            {/each}
        </Select>

        <Select label="Sparte" bind:value={sectionId} on:change={onSection}>
            {#each sections as section}
                <option value={section.id}>{section.name}</option>
            {/each}
        </Select>

        <Select label="Gruppe" bind:value={group} on:change={onGroup} disabled={sectionId === 5}>
            {#each groups as group}
                <option value={group}>{group}</option>
            {/each}
        </Select>
    </div>
    <div class='w-8 justify-center m-2 circled bg-alert cursor-pointer' on:click={onHelp}>?</div>
</div>



<style>

</style>