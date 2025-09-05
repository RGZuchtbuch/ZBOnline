<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';

	let { district } = $props();

</script>

{#if district }
	<section class='m-64 mt-32'>
		<h3 class=''>
			Den Verband {district.name} kann man hier verwalten. .
		</h3>

		<ol class='ml-16'>
			<li><a href={`/moderator/district/${ctx.district.id}/result`}>Leistungen</a></li>
			<li><a href={`/moderator/district/${ctx.district.id}/breeder`}>Züchter</a></li>
		</ol>
	</section>
{/if}


<style>

</style>