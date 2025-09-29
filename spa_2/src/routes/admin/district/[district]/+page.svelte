<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx } from '$lib/js/store.svelte.js';

	import District from '$lib/cmp/moderator/District.svelte';

	let mounted = $state( false );

	let district = ctx.federation.districts[ +page.params.district ]; // ctx.district may not be known yet

	$effect( () => {
		if( ctx.district ) setHeader();
	});

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}`;
		ctx.submenu = [
			{name: 'Leistungen', href: `/admin/district/${ctx.district.id}/result`},
			{name: 'Züchter', href: `/admin/district/${ctx.district.id}/breeder`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Admin', href: '/admin'},
			{name: 'Verbände', href: '/admin/district'},
			{name: 'Verband' },
		];
	}

	onMount( () => { mounted = true })

</script>

{#if ctx.district && mounted}
	<main class='' in:fade={{duration:cfg.fadeIn}}>
		<District district={ctx.district}/>
	</main>
{/if}

