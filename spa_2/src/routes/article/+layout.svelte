<script>
    import { onMount } from 'svelte';
    import { ctx } from '$lib/js/store.svelte.js';
    import { Article } from '$lib/js/model.js';

    let { children, data } = $props();

    onMount( async () => {
       ctx.articles = await Article.query();
       console.log( 'Mounted Articles', ctx.articles.length );
    })

</script>

{#if ctx.articles }
   {@render children()}
{/if}

<style>
</style>