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
		if( dirty.breeder && page.url ) await loadBreeder( +page.params.breeder );
	})


	async function loadBreeder( id ) {
		ctx.breeder = await model.Breeder.load(id);
	}

</script>

{#if ctx.breeder}
	{@render children()}
{/if}



