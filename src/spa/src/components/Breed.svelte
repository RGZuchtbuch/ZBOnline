<script>
    import { active, meta, router, Route } from 'tinro';
    import Box from '../components/Box.svelte';

    const route = meta();

    console.log( 'Breed' );

    export let promise;
</script>

{#await promise}
    loading...
{:then data}
    <Box legend={'Rasse : '+data.breed.name}>
        <div class='flex flex-row'>
            <div class='grow'>
                {data.breed.name} ({data.breed.id})
            </div>
            <Box legend='Farben'>
                <ul class='w-48 h-32 overflow-y-auto'>
                    {#each data.breed.colors as color }
                        <li> → <a href='{route.match}/color/{color.id}'>{color.name}</a></li>
                    {/each}
                </ul>
            </Box>
        </div>

    </Box>
{:catch error}
    Rasse nicht gefunden: ({ error.response.status })
{/await}
