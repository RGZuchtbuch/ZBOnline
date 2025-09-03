<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx} from '$lib/js/store.svelte.js';
	import { fullName, shortName } from '$lib/js/tools.js';
	import Districts from '$lib/cmp/moderator/Districts.svelte';
	import Moderator from '$lib/cmp/moderator/Moderator.svelte';

	$effect( () => {
		if( page.url && ctx.user ) setHeader();
	});

	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verbände zum verwalten`;
		ctx.submenu = [
//			{name: 'Verbände', href: `/moderator/district`},
		];
		ctx.crumbs = [
			{name: `Obmann`, href:'/moderator' },
			{name: `Verbände` },
		];
	}

</script>

{#if ctx.districts}
	<main class='' in:fade={{duration:cfg.fadeIn}}>
		<Districts districts={ctx.districts}/>
	</main>
{/if}



