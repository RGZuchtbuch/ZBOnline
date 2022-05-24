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

        <div class='w-full'>Loading Rassestandard</div>
    {:then data}

        <ul class='w-full'>
            {#if data.sections.children}
                {#each data.sections.children as section}
                    <li> → <a href='{route.match}/section/{section.id}'>{section.name}</a> ({section.children.length})</li>
                    {#if section.children}
                        {#each section.children as child}
                            <li class='pl-4'> → <a href='{route.match}/section/{child.id}'>{child.name}</a>{child.children ? '('+child.children.length+')' : '' }</li>
                            {#if child.children}
                                {#each child.children as grandchild}
                                    <li class='pl-8'> → <a href='{route.match}/section/{grandchild.id}'>{grandchild.name}</a></li>
                                {/each}
                            {/if}
                        {/each}
                    {/if}
                {/each}
            {/if}
        </ul>

    {:catch error}
        error
    {/await}
</Box>
<style>

</style>