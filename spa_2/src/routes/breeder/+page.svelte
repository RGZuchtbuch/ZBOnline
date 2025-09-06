<script>
	import { onMount } from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx } from '$lib/js/store.svelte.js';
	import {shortName, fullName } from '$lib/js/tools.js';

	import Breeder from '$lib/cmp/breeder/Breeder.svelte';

	let mounted = $state( false );

	$effect( () => {
		if( ctx.breeder ) setHeader();
	});

	function setHeader() {
		ctx.menustate[ '/breeder' ] = page.url.href;
		ctx.title = `Züchter ${fullName(ctx.breeder)}`;
		ctx.submenu = [
			{name: 'Stämme', href: `/breeder/pair`},
			{name: 'Mitglied', href: `/breeder/profile`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: `Züchter` },
		];
	}
	onMount( () => mounted = true );

</script>

{#if ctx.breeder && mounted}
	<main in:fade={{duration:cfg.fadeIn}}>
		<Breeder breeder={ctx.breeder} district={ctx.district} />
	</main>
{/if}


<style>

</style>
