<script>
	import { fade } from 'svelte/transition';
	import { page } from '$app/state';
	import { goto } from '$app/navigation';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { addCrumb, fullName, shortName, txt} from '$lib/js/tools.js';
	import model from '$lib/js/model.js';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';


	let { children } = $props();


	$effect( async () => {
		const breederId = +page.params.breeder;
		if( dirty.breeder || page.url ) await loadBreeder( breederId );
	})


	async function loadBreeder( id ) {
		dirty.breeder = false;
		ctx.breeder = null;
		ctx.breeder = await model.Breeder.load( id );
	}

</script>

{#if ctx.breeder}
	<div in:fade>
		{@render children()}
	</div>
{/if}



