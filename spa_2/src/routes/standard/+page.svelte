<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx } from '$lib/js/store.svelte.js';

	import Standard from '$lib/cmp/standard/Standard.svelte';
	import {onMount} from 'svelte';

	let mounted = $state( false ); // to force transition

	$effect( () => {
		if( page.url ) setHeader();
	})

	function setHeader() {
		ctx.title = `BDRG Rassegeflügel Standard`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Standard', href: '/standard'},
		];
	}

	onMount( () => mounted = true );

</script>

{#if ctx.standard && mounted}
	<main class='' in:fade={{duration:cfg.fadeIn}}>
		<Standard standard={ctx.standard}/>
	</main>
{/if}
