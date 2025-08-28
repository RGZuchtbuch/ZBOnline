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
		await loadBreeder( +page.params.breeder );
	})


	async function loadBreeder( id ) {
//		dirty.breeder = false;
//		ctx.breeder = null;
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



