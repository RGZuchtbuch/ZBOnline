<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx} from '$lib/js/store.svelte.js';
	import { addCrumb } from '$lib/js/tools.js';

	import Districts from '$lib/cmp/district/Districts.svelte';

	let mounted = $state( false );

	$effect( () => {
		if( page.url ) setHeader();
	});

	function setHeader() {
		ctx.title = 'Landesverbände im BDRG Zuchtbuch';
		ctx.submenu = [];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Verbände'},
		];
	}

	onMount( () => mounted = true );

</script>

{#if ctx.federation && mounted}
	<main in:fade={{duration:cfg.fadeIn}}>
		<Districts root={ctx.federation}/>
	</main>
{/if}

