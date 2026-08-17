<script>
	import { page } from '$app/state';

	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	let { children } = $props();

	$effect( async () => {
		if( dirty.breeder && page.params.breeder ) await loadBreeder( +page.params.breeder );
	})


	async function loadBreeder( id ) {
		ctx.breeder = await model.Breeder.load(id);
	}

</script>

{#if ctx.breeder}
	{@render children()}
{/if}



