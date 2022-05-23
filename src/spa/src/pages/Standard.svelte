<script>
    import { active, meta, router, Route } from 'tinro';
    import dic from '../scripts/dic.js';
    import Box from '../components/Box.svelte';

    const route = meta();

    console.log( 'Sections', route );

    export let promise;
    export let legend='';
</script>

<Box legend={legend}>
    {#await promise}
        loading....

    {:then parent}
            <ul>
                {#each parent.children as child}
                    <li> → <a href='{route.match}/{child.id}'>{child.name}</a></li>
                    {#if child.children}
                        {#each child.children as grandchild}
                            <li class='pl-4'> → <a href='{route.match}/{grandchild.id}'>{grandchild.name}</a></li>
                        {/each}
                    {/if}
                {/each}
            </ul>
    {:catch error}
        error
    {/await}
</Box>


<style>

</style>