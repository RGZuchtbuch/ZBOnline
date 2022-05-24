<script>
    import { active, meta, router, Route } from 'tinro';
    import Box from './Box.svelte';

    const route = meta();

    export let promise
    console.log( 'Section' );
</script>

{#await promise}
    loading...
{:then data}
    <Box legend='Rassen der {data.section.name}'>
        <ul class='w-128 flex flex-col'>
            {#each data.section.breeds as breed }
                <li class='pl-8'> → <a href='/#/standard/breed/{breed.id}'>{breed.name}</a></li>
            {/each}
        </ul>
    </Box>
{:catch error}
    oeps
{/await}



