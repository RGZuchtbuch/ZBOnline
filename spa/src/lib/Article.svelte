<script>
    import api from '../js/api.js';

    export let id = null;

    let article = null;

    function loadArticle( id ) {
        api.article.get( id ).then( response => {
            article = response.article;
        });
    }

    $: loadArticle( id );
</script>

{#if article}
    <h2 class='rounded-t p-2 bg-header text-center text-xl print'> {article.title} </h2>
    <div class='bg-white overflow-y-scroll border border-gray-600 border-t-gray-400 rounded-b scrollbar'>
        {article.text}
    </div>
{:else}
    NO PAGE FOUND
{/if}
<style>

</style>
