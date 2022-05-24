<script>
    import {active, meta, router, Route} from 'tinro';
    import dic from '../scripts/dic.js';
    import Box from '../components/Box.svelte';

    const route = meta();

    console.log('Districts', route);

    export let promise;
    export let legend = '';

</script>

<Box legend={legend}>
    {#await promise}

        <div class='w-full'>Loading Districts...</div>
    {:then data}
        {#if data.district }
            <ul class='w-full'>
                <li>
                    → <a href='/#/district/{data.district.id}'>{data.district.name}</a> {data.district.children ? '('+data.district.children.length+')' : ''}
                </li>
                {#if data.district.children}
                    {#each data.district.children as district}
                        <li class='pl-4'>
                            → <a href='/#/district/{district.id}'>{district.name}</a> {district.children ? '('+district.children.length+')' : ''}
                        </li>
                        {#if district.children}
                            {#each district.children as district}
                                <li class='pl-8'>
                                    → <a href='/#/district/{district.id}'>{district.name}</a> {district.children ? '('+district.children.length+')' : ''}
                                </li>
                                {#if district.children}
                                    {#each district.children as district}
                                        <li class='pl-12'>
                                            → <a href='/#/district/{district.id}'>{district.name}</a> {district.children ? '('+district.children.length+')' : ''}
                                        </li>
                                    {/each}
                                {/if}
                            {/each}
                        {/if}
                    {/each}
                {/if}
            </ul>
        {/if}

    {:catch error}
        error in districts
    {/await}
</Box>
<style>

</style>