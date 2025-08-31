<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx } from '$lib/js/store.svelte.js';

	import Lineage from '$lib/cmp/toolbox/Lineage.svelte';


	$effect( () => {
		if( true ) {
			setHeader();
		}
	});

	function setHeader() {
		ctx.menustate[ '/tool' ] = page.url.href;

		ctx.title = `BDRG Zuchtbuch, Abstammungsnachweis`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Toolbox', href: '/tool'},
			{name: 'Abstammungsnachweis'},
		];
	}

</script>

<main class='' in:fade={{duration:cfg.fadeIn}}>
	<Lineage />
</main>
