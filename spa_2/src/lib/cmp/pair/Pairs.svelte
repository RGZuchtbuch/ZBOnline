<script>
	import { page } from '$app/state';
	let { pairs } = $props();
</script>

<h3>Pairs a</h3>
<div class='flex flex-col'>
	{#each pairs as pair, i}
		<a href={$page.url.pathname+'/'+pair.id}>{pair.name}</a>
	{/each}
</div>