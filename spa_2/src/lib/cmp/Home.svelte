<script>
	import Article from '$lib/cmp/article/Article.svelte';

	let { articles } = $props();
</script>

<section class='p-6'>
	{#if articles}
		{#each articles as article, i}
			{#if i>0} <hr> {/if}
			<Article {article}/>
		{/each}
	{/if}
</section>

