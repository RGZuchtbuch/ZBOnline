<script>
	import { fade, fly, slide } from 'svelte/transition';
	let {articles} = $props()

</script>


<h2 class='header'>Alle Artikel zum Zuchtbuch</h2>
<ol in:slide>
	{#each articles as article, i}
		<li>
			<a class='grow' href={`/article/${article.id}`}>
				<div class='text-right '>{i+1}.</div>
				<div class='grow'>{article.title}</div>
				<div class='w-32'>{article.author}</div>
			</a>
		</li>
	{/each}
</ol>

<style>
	a {
		@apply flex flex-row border-b p-2 gap-x-2;
	}
	ol {
		@apply mx-16 my-4;
	}
</style>
