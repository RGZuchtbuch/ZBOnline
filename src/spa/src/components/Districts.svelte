<script>
    import {active, meta, router, Route} from 'tinro';
    import dic from '../scripts/dic.js';
    import SelectTree from './SelectTree.svelte';

    const route = meta();

    export let promise;
    export let legend = '';
    export let link='';

    let districts = null;

    promise.then( data => {
        districts = data;
    })

</script>

<main>
    <div>{legend}</div>
    {#await promise then districts}
        <SelectTree children={districts} {link}/>
    {/await}
</main>

<style>

</style>