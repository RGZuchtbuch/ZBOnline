<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx} from '$lib/js/store.svelte.js';
	import { fullName, shortName } from '$lib/js/tools.js';

	import Profile from '$lib/cmp/breeder/profile.svelte';
	import {onMount} from 'svelte';

	let mounted = $state( false );

	$effect( async () => {
		if( ctx.breeder ) setHeader();
	});


	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.short}, Züchter ${fullName(ctx.breeder)}`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Admin', href: '/admin'},
			{name: 'Verbände', href: `/admin/district`},
			{name: 'Verband', href: `/admin/district/${ctx.district.id}`},
			{name: 'Züchter', href: `/admin/district/${ctx.district.id}/breeder`},
			{name: `${fullName(ctx.breeder)}`, href:`/admin/district/${ctx.district.id}/breeder/${ctx.breeder.id}`},
			{name: 'Mitglied' },
		];
	}

	onMount( () => mounted = true );


</script>

{#if ctx.breeder && ctx.district && mounted}
	<main in:fade={{duration:cfg.fadeIn}}>
		<Profile bind:breeder={ctx.breeder} district={ctx.district} />
	</main>
{/if}
