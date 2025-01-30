<script>
    import { createEventDispatcher } from 'svelte';
    import {router} from "tinro";
    import Select from '../../common/form/input/Select.svelte';

//    export let year;
    export let sectionId;
    export let group;

    const dispatch = createEventDispatcher();

    let years = getYears();

    let sections = [ // for selects
        {id: 3, name: 'Groß & Wassergeflügel'},
        {id: 11, name: 'Hühner (Groß)'}, { id: 12, name: 'Zwerghühner' }, {id: 13, name: 'Wachteln'},
        {id: 5, name: 'Tauben'},
        {id: 6, name: 'Ziergeflügel'},
        {id: 9999, name: 'AOC Klasse'}
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

    function getYears() {
        const thisYear = new Date().getFullYear();
        const years = [];
        for( let year=thisYear; year>=STARTYEAR; year-- ) {
            years.push( year );
        }
        return years;
    }

</script>



<div class='w-256 flex flex-row border border-gray-400 bg-gray-200 gap-x-4 justify-center'>
    <!--Select label="Jahr" value={year} on:change={onYear} title='Leistungsjahr'>
        {#each years as y}
            <option value={y} selected={ y === year }>{y}</option>
        {/each}
    </Select -->

    <Select label="Sparte" bind:value={sectionId} on:change={onSection} title='Sparte zum Eingeben'>
        {#each sections as section}
            <option value={section.id}>{section.name}</option>
        {/each}
    </Select>

    <Select label="Gruppe" bind:value={group} on:change={onGroup} disabled={sectionId === 5} title='Zuchtbuchgruppe I, II oder III'>
        {#each groups as group}
            <option value={group}>{group}</option>
        {/each}
    </Select>
</div>



<style>

</style>