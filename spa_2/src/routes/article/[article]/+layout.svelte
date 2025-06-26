<script>
    import { onMount } from 'svelte';
    import { page } from '$app/state';
    import { ctx } from '$lib/js/store.svelte.js';
    import { Article } from '$lib/js/model.js';

    let { children, data } = $props();

    onMount( async () => {
       ctx.article = await Article.load( page.params.article );
       console.log( 'Mounted Articles', ctx.articles.length );
    })

</script>

{#if ctx.articles }
   {@render children()}
{/if}

<style>
</style>