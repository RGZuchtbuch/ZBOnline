<script>
	import { fade, fly, slide } from 'svelte/transition';
	let {articles} = $props()

</script>


<p>
	Das Zuchtbuch ...
</p>
<hr>
<h4>Artikel im Zuchtbuch</h4>
<ul in:slide>
	{#each articles as article}
		<li>&#8231; <a href={`/article/${article.id}`}>{article.title}</a></li>
	{/each}
</ul>
