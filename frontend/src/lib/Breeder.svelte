<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    export let promise;
    export let legend = '';
    export let link='';

    let breeder = null;

    onMount( () => {
        if( promise ) promise.then( data => {
            breeder = data;
        });
    })


</script>

<main>
    <div>{legend}</div>
    {#if breeder}
        Breeder {breeder.id}
    {/if}
</main>

<style>

</style>