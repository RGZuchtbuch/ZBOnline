<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import {page} from '$app/state';
	import Grader from '$lib/cmp/tools/Grader.svelte';

	//let { data } = $props();

	console.log( 'page grader' );

	// $effect( () => {
	// 	ctx.breeder = data.breeder;
	// });

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

<Grader />
