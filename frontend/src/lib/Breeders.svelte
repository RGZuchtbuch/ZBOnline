<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import SelectTree from './SelectTree.svelte';

    const route = meta();

    export let promise;
    export let legend = '';
    export let link='';

    let breeders = null;

    onMount( () => {
        if( promise ) promise.then( data => {
            breeders = data;
        });
    })


</script>

<main>
    <div>{legend}</div>
    {#if breeders}
        Breeders
    {/if}
</main>

<style>

</style>