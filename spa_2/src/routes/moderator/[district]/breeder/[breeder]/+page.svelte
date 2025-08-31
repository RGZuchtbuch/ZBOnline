<script>

	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
	import { addCrumb, fullName, shortName, txt} from '$lib/js/tools.js';
	import {onMount} from 'svelte';

	let mounted = $state( false );

	$effect(async () => {
		if( ctx.district !== null && ctx.breeder !== null ) setHeader();
	})

	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.short}, Züchter ${fullName(ctx.breeder)}`;
		ctx.submenu = [
			{name: 'Stämme', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/pair`},
			{name: 'Mitglied', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/profile`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
			{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
			{name: ctx.breeder.id === 0 ? 'Neu' : `${fullName(ctx.breeder)}`},
		];
	}

	onMount( () => mounted = true );

</script>

{#if ctx.breeder && mounted}
	<main class='flex flex-col mt-32 items-center' in:fade={{duration:cfg.fadeIn}}>
		<h2>Züchter verwaltung von {`${ fullName( ctx.breeder )}`}</h2>
		<p>Hier kannst du die
			<a href={`/moderator/${ctx.breeder.districtId}/breeder/${ctx.breeder.id}/pair`}>
				Stämme/Paare
			</a>
			und
			<a href={`/moderator/${ctx.breeder.districtId}/breeder/${ctx.breeder.id}/profile`}>
				Mitgliedsdaten
			</a>
			des Züchters verwalten
		</p>
		<ul>
			<li><a href={`/moderator/${ctx.breeder.districtId}/breeder/${ctx.breeder.id}/pair`}>Stämme</a>
			<li><a href={`/moderator/${ctx.breeder.districtId}/breeder/${ctx.breeder.id}/profile`}>Mitgliedsdaten</a>
		</ul>
	</main>
{/if}



