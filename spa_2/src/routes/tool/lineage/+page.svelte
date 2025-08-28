<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import {page} from '$app/state';
	import Lineage from '$lib/cmp/tools/Lineage.svelte';

	console.log( 'Lineage' );

	$effect( () => {
		if( true ) {
			setHeader();
		}
	});

	function setHeader() {
		ctx.menustate[ '/tool' ] = page.url.href;

		ctx.title = `Abstammungsnachweis`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Toolbox', href: '/tool'},
			{name: 'Abstammungsnachweis'},
		];
	}

</script>

<Lineage />
