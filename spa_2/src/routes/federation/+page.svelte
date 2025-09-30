<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';

	import { cfg, ctx } from '$lib/js/store.svelte.js';

	import Districts from '$lib/cmp/district/Districts.svelte';

	let mounted = $state( false ); // to trigger transition

	onMount( () => {
		if( page.url ) setHeader();
		mounted = true;
	});

	function setHeader() {
		ctx.title = 'Landesverbände im BDRG Zuchtbuch';
		ctx.submenu = [];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Verbände'},
		];
	}
</script>

<section in:fade={{duration:cfg.fadeIn}}>
	<Districts districtsRoot={ctx.federation}/>
</section>

