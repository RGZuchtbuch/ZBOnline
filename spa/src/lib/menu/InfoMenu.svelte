<script>
    import {Route, meta, router} from 'tinro';
    import api from "../../js/api.js";

    let articles;

    const route = meta();

    function loadArticles() {
        api.article.getAll().then( response => {
            articles = response.articles;
        })
    }

    loadArticles()
</script>

<div class='flex flex-col'>

    <h4>Zuchtbuch Infos</h4>
    {#if articles}
        <ul>
            {#each articles as article}
                <li> <a href={route.match+'/'+article.id} title='Zur Info'>→ {article.title} </a> </li>
            {/each}
        </ul>
    {/if}
</div>

<style>

</style>