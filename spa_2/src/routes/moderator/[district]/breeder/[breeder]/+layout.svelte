<script>

	import { page } from '$app/state';
	import { goto } from '$app/navigation';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { addCrumb, fullName, shortName, txt} from '$lib/js/tools.js';
	import model from '$lib/js/model.js';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';


	let { children } = $props();

	addCrumb( { name:`${shortName(ctx.breeder)}`, href:page.url.href } );

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

Layout Breeder
{@render children()}



