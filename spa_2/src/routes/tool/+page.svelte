<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import Tools from '$lib/cmp/tools/Tools.svelte';


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

<Tools/>



<style>
	ul {
		@apply list-inside list-disc text-red-600;
	}
</style>