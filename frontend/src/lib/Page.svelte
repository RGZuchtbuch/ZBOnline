<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../js/api.js';


    export let pageId = null;
    let page = null;

    const route = meta();

    function loadPage( pageId ) {
        api.page.get( pageId ).then( response => {
           page = response.page;
        });
    }

    onMount( () => {
    })
    $: loadPage( pageId );
</script>

{#if page}
    <h2 class='rounded-t p-2 bg-header text-center text-xl print'> {page.title} </h2>
    <div class='bg-white overflow-y-scroll border border-gray-600 border-t-gray-400 rounded-b scrollbar'>
        {page.text}
    </div>
{:else}
    NO PAGE FOUND
{/if}
<style>

</style>
