<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';

	let { district } = $props();

</script>

{#if district }
	<section class='m-16 p-6'>
		<h3 class=''>
			Den Verband {district.name} kann man hier verwalten. .
		</h3>

		<ol>
			<li>Leistungen</li>
			<li>Züchter</li>
		</ol>



	</section>
{/if}


<style>

</style>