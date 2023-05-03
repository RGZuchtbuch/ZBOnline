<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { txt } from '../../js/util.js';
    import { user } from '../../js/store.js'
    import BreederRow from './BreederListRow.svelte';
    import Button from '../common/input/Button.svelte';
    import Select from '../common/input/Select.svelte';
    import Text from '../common/input/Text.svelte';


    export let breeders = null;

    let showAll = false; // should ex members be included ?

    let districts = null;

    const route = meta();



//    $: onChange( breeders );

    console.log( 'Date',  );

</script>


<div class='flex flex-row border border-gray-400 rounded-t px-4 py-1 bg-header gap-x-1 font-bold'>
    <div class='w-4'>Id</div>
    <div class='w-56'>Name</div>
    <div class='w-36'>Ortsverein</div>
    <div class='w-64'>ZB Verband</div>
    <div class='w-24'>Seit</div>
    <div class='w-24'>InAktive <input type='checkbox' bind:checked={showAll}> </div>
</div>

{#if breeders}
    {#each breeders as breeder}
        <BreederRow {breeder} {showAll} />
    {/each}
{/if}



<style>

</style>