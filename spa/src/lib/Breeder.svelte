<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    export let breeder;

    onMount( () => {
    })


</script>

<main>
    <div>In Breeder</div>
    {#if breeder}
        Breeder {breeder.id} {breeder.name}
    {/if}
</main>

<style>

</style>