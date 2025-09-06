<script>

	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import {activeYear} from '$lib/js/tools.js';
	import {page} from '$app/state';

	let { children } = $props();

	$effect( () => {
		ctx.district = ctx.federation.districts[ page.params.district ];
	});


</script>

{#if ctx.district}
	{@render children()}
{/if}

