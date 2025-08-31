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
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.short}, Züchter ${fullName(ctx.breeder)}`;
		ctx.submenu = [
			//{name: 'Stämme', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/pair`},
//			{name: 'Mitglied', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/profile`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
			{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
			{name: `${fullName(ctx.breeder)}`, href:`/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}`},
			{name: 'Mitglied' },
		];
	};

	onMount( () => mounted = true );


</script>

{#if ctx.breeder && ctx.district && mounted}
	<main in:fade={{duration:cfg.fadeIn}}>
		<Profile bind:breeder={ctx.breeder} district={ctx.district} />
	</main>
{/if}
