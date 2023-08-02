<script>
    import api from '../../js/api.js';

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
        <h2 class=' w-256 rounded-t p-2 bg-header text-white text-center text-xl print'> {article.title} </h2>
        <div class='w-256 bg-white overflow-y-scroll border border-gray-600 border-t-gray-400 rounded-b scrollbar p-8'>
            {@html article.html}
        </div>
    {:else}
        Seite wird geladen.....
    {/if}


<style>

</style>
