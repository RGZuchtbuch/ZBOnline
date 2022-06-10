<script>
    import {active, meta, router, Route} from 'tinro';
    import dic from '../scripts/dic.js';
    import Box from '../components/Box.svelte';

    const route = meta();

    export let promise;
    export let legend = '';

    let districts = null;
    promise.then( response => {
        districts = response.districts;
        console.log( 'Districts', districts );
    })

</script>

<Box legend={legend}>
    {#if ! districts}
        <div class='w-full'>Loading Districts...</div>
    {:else}
        {#each districts as district}
            <ul class='w-full'>
                <li>
                    1 → <a href='/#/district/{district.id}'>{district.name}</a> {district.children ? '('+district.children.length+')' : ''}
                </li>
                {#if district.children}
                    {#each district.children as district}
                        <li class='pl-4'>
                            2 → <a href='/#/district/{district.id}'>{district.name}</a> {district.children ? '('+district.children.length+')' : ''}
                        </li>
                        {#if district.children}
                            {#each district.children as district}
                                <li class='pl-8'>
                                    3 → <a href='/#/district/{district.id}'>{district.name}</a> {district.children ? '('+district.children.length+')' : ''}
                                </li>
                                {#if district.children}
                                    {#each district.children as district}
                                        <li class='pl-12'>
                                            4 → <a href='/#/district/{district.id}'>{district.name}</a> {district.children ? '('+district.children.length+')' : ''}
                                        </li>
                                    {/each}
                                {/if}
                            {/each}
                        {/if}
                    {/each}
                {/if}
            </ul>
        {/each}
    {/if}
</Box>
<style>

</style>