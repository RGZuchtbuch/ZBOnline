<script>
	import {page} from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx } from '$lib/js/store.svelte.js';

	import Grader from '$lib/cmp/toolbox/Grader.svelte';

	$effect( () => {
		if( true ) {
			setHeader();
		}
	});

	function setHeader() {
		ctx.menustate[ '/tool' ] = page.url.href;

		ctx.title = `Leistungsrechner`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Toolbox', href: '/tool'},
			{name: 'Leistungsrechner'},
		];
	}

</script>

<main class='' in:fade={{duration:cfg.fadeIn}}>
	<Grader />
</main>
