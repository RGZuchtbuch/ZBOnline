<script>
	import { page } from '$app/state';
	let { data } = $props();
</script>

<h3>Pairs</h3>
<div class='flex flex-col'>
	{#each data.pairs as pair, i}
		<a href={$page.url.pathname+'/'+pair.id}>{pair.name}</a>
	{/each}
</div>