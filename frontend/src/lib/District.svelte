<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    export let promise;
    export let legend = '';
    export let link='';

    let district = null;

    onMount( () => {
        if( promise ) promise.then( data => {
            district = data;
        });
    })


</script>

<main>
    <div>{legend}</div>
    {#if district}
        District {district.id}
    {/if}
</main>

<style>

</style>