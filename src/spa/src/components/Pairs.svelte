<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import dic from '../scripts/dic.js';
    import SelectTree from './SelectTree.svelte';

    const route = meta();

    export let promise;
    export let legend = '';
    export let link='';

    let pairs = null;

    onMount( () => {
        promise.then( data => {
            pairs = data;
        });
    });

    promise.then( data => {
        districts = data;
    })

</script>

<main>
    <div>{legend}</div>
    <div class="flex flex-col">
        {#await promise then pairs}
            {#each pairs.years as year}
                {#each year.section as section}
                    <div class='flex flex-row justify-between'>
                        <div>{year.year}</div>
                        <div>{section.name}</div>
                        <div></div>
                    </div>
                    {#each section.breeds as breed}
                        <div>{breed.name}</div>
                        {#each breed.colors as color}
                            <div>{color.name}</div>
                            {#each color.pairs as pair}
                                <div class='flex flex-row'>
                                    <div>{pair.name}</div>
                                    <div>{pair.parents}</div>
                                    <div>{pair.lay}</div>
                                    <div>{pair.brood.eggs}</div>
                                    <div>{pair.brood.fertile}</div>
                                    <div>{pair.brood.hatched}</div>
                                    <div>{pair.show.count}</div>
                                    <div>{pair.show.score}</div>
                                </div>
                            {/each}
                        {/each}
                    {/each}
                {/each}
            {/each}
        {/await}
    </div>
</main>

<style>

</style>