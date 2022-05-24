<script>
    import { active, meta, router, Route } from 'tinro';

    import Box from '../components/Box.svelte';

    const route = meta();

    console.log( 'Color' );

    export let promise;
</script>

{#await promise}
    loading...
{:then data}
    <Box legend={'Farbenschlag : '+data.color.name}>
        {data.color.name} ({data.color.id})
    </Box>
{:catch error}
    Oeps
{/await}
