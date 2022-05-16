<script>
    import { slide } from 'svelte/transition';
    import api from '../../../scripts/api.js';

    export let promise;
    export let onSectionSelect = null;

    function onClick( event ) {
        if( onSectionSelect ) onSectionSelect( this.id );
    }

    let sections = [];
    if( promise ) promise.then( (data) => {
        sections = data;
    })



    console.log( 'run sections' )

    $: console.log( 'Sections', sections );

</script>

{#if sections}
    <fieldset class='bordered w-256'>
        <legend> Abteilungen </legend>
        <ul>
            {#each sections as section}
                <li on:click={onClick.bind( section )}> {section.name}</li>
            {/each}
        </ul>
    </fieldset>
{/if}

<style>

</style>