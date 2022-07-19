<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    export let promise;
    export let legend = '';

    let reports = null;

    onMount( () => {
        if( promise ) promise.then( data => {
            reports = data;
        });
    })


</script>

<main>
    <div>{legend}</div>
    {#if reports}
        Reports ({reports.length})
    {/if}
</main>

<style>

</style>