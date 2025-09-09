<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx} from '$lib/js/store.svelte.js';
	import Profile from '$lib/cmp/breeder/profile.svelte';
	import { fullName } from '$lib/js/tools.js';
	import {onMount} from 'svelte';

	let mounted = $state( false );

	$effect( async () => {
		if( ctx.breeder ) setHeader();
	} );

	function setHeader() {
		ctx.menustate[ '/breeder' ] = page.url.href;


		ctx.title = `Züchter ${fullName(ctx.breeder)}, Mitglied`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: `Züchter`, href: `/breeder` },
			{name: 'Mitglied'},
		];
	}

	onMount( () => mounted = true );



</script>

{#if ctx.breeder && ctx.district && mounted }
	<main in:fade={{duration:cfg.fadeIn}}>
		<Profile bind:breeder={ctx.breeder} district={ctx.district} />
	</main>
{/if}
