<script>
    import { active, meta, router, Route } from 'tinro';
    import Box from './Box.svelte';

    const route = meta();

    console.log( 'Breeds' );

    export let promise;
    export let legend = '?';
</script>

{#await promise}
    loading...
{:then breeds}
    <Box legend={legend}>
        <ul>
            {#each breeds as breed}
                <li> → <a href='{route.match}/{breed.id}'>{breed.name} ({breed.id})</a> </li>
            {/each}
        </ul>
    </Box>
{:catch error}
    Oeps
{/await}
