<script>
	import { page } from '$app/state';
	let {breeders} = $props();

</script>

Breeders {breeders.length}
{#each breeders as breeder}
	<a href={page.url.pathname+'/'+breeder.id}>{breeder.firstname} {breeder.infix} {breeder.lastname}</a>
{/each}