<script>
	import { page } from '$app/state';

	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	let { children } = $props();

	$effect( async () => {
		//const breederId = +page.params.breeder;
		if( dirty.breeder && page.url ) await loadBreeder( +page.params.breeder );
		//await loadBreeder( +page.params.breeder );
	})

	async function loadBreeder( id ) {
		if( id === 0 ) {
			ctx.breeder = model.Breeder.new( ctx.district.id );
		} else {
			ctx.breeder = await model.Breeder.load(id);
		}
	}

</script>

{#if ctx.breeder}
	{@render children()}
{/if}



