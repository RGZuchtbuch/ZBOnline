<script>
    import api from '../../js/api.js';
    import Page from "../common/Page.svelte";

    export let articleId = null;

    let article = null;

    function loadArticle( id ) {
        console.log( "ArticleId", id );
        api.article.get( id ).then( response => {
            article = response.article;
            console.log( 'Article', article );
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
