<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx } from '$lib/js/store.svelte.js';

	import Tools from '$lib/cmp/toolbox/Tools.svelte';


 	$effect( async () => {
		setHeader();
 	});

	function setHeader() {
		ctx.menustate[ '/tools' ] = page.url.href;

		ctx.title = `Züchter und Obmann Toolbox`;
		ctx.submenu = [
			{name: 'Leistungsrechner', href: '/tool/grader'},
			{name: 'Abstammungsnachweis', href: '/tool/lineage'},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Toolbox'},
		];
	}


</script>

<main class='' in:fade={{duration:cfg.fadeIn}}>
	<Tools/>
</main>



<style>
	ul {
		@apply list-inside list-disc text-red-600;
	}
</style>