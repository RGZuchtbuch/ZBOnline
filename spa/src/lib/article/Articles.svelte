<script>
    import {createEventDispatcher, onMount} from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import { user } from '../../js/store.js'
    import api from '../../js/api.js';

    import ArticleRow from './ArticleRow.svelte';
    import ScrollDiv from '../common/ScrollDiv.svelte'


    let articles = [];

    const route = meta();
    const dispatch = createEventDispatcher();

    function loadArticles() {
        api.article.getAll().then( response => {
            articles = response.articles;
        })
    }

    onMount( () => {})

    loadArticles();
</script>

{#if $user && $user.admin}
    <h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-white text-center text-xl print'>Die RGZuchtbuch Beiträge</h2>
    <div class='w-256 bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 scrollbar'>
        {#each articles as article}
                <ArticleRow {article} />
        {/each}
    </div>
{:else}
    Geht nicht, Sollte nicht
{/if}

<style></style>