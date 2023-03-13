<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import Section from './RecursiveSection.svelte';

    let rootSection = null;

    const route = meta();

    function loadSections() {
        api.section.root.get( 2 ).then( response => {
            rootSection = response.section;
        } );
    }

    onMount( () => {})

    loadSections();
</script>


<h2 class='border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Der Rassestandard im BDRG</h2>
{#if rootSection}
    <div class='bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
        <Section section={rootSection} />
    </div>
{:else}
    Einen moment bitte
{/if}


<style>

</style>
