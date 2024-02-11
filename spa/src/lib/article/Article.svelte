<script>
    import api from '../../js/api.js';
    import Page from "../common/Page.svelte";

    export let articleId = null;

    let article = null;

    function loadArticle( id ) {
        article = null; // to force redraw
        api.article.get( id ).then( response => {
            article = response.article;
        });
    }

    $: loadArticle( articleId );
</script>


{#if article}
    <Page>
        <div slot='title'> {article.title} </div>
        <div slot='header'> - </div>
        <div slot='body'> {@html article.html} </div>
    </Page>
{:else}
    Seite wird geladen.....
{/if}


<style>



</style>
