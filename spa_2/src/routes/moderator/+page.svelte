<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx} from '$lib/js/store.svelte.js';
	import { fullName, shortName } from '$lib/js/tools.js';
	import Districts from '$lib/cmp/moderator/Districts.svelte';

	$effect( () => {
		if( ctx.district ) setHeader();
	});

	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verbände zum verwalten`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: `Obmann` },
		];
	}

</script>

{#if ctx.districts}
	<main class='' in:fade={{duration:cfg.fadeIn}}>
		<Districts districts={ctx.districts}/>
	</main>
{/if}



