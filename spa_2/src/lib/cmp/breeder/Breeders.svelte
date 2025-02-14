<script>
	import { page } from '$app/stores';
	export let breeders;
</script>

<div class='flex flex-col text-center'>
	{#each breeders as breeder}
		<a href={$page.url.pathname+'/'+breeder.id}>{breeder.name}</a>
	{/each}
</div>