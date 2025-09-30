<script>
	import { fade } from 'svelte/transition';
	import { page } from '$app/state';
	import { cfg, ctx } from '$lib/js/store.svelte.js';
	import { fullName } from '$lib/js/tools.js';
	import Pair from '$lib/cmp/pair/Pair.svelte';
	import model from '$lib/js/model.js';
	import {onMount} from 'svelte';

	let mounted = $state( false );

	$effect( async () => {
		if( page.url ) await loadPair( +page.params.pair );
	})

	$effect( async () => {
		if( ctx.district && ctx.breeder && ctx.pair ) setHeader();
	});

	async function loadPair( id ) {
		ctx.pair = null;
		ctx.pair = id === 0 ?
			await model.Pair.new( ctx.breeder) : // new for this breeder
			await model.Pair.load( id );
	}

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.short} : Züchter ${fullName(ctx.breeder)}, Stämm ${ctx.pair.year % 100}.${ctx.pair.name}`;
		ctx.submenu = [
//			{name: 'Stämme', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/pair`},
//			{name: 'Mitglied', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/profile`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Admin', href: '/admin'},
			{name: 'Verbände', href: `/admin/district/${ctx.district.id}`},
			{name: 'Verband', href: `/admin/district/${ctx.district.id}`},
			{name: 'Züchter', href: `/admin/district/${ctx.district.id}/breeder`},
			{name: `${fullName(ctx.breeder)}`, href:`/admin/district/${ctx.district.id}/breeder/${ctx.breeder.id}`},
			{name: 'Stämme', href: `/admin/district/${ctx.pair.districtId}/breeder/${ctx.pair.breederId}/pair`},
			{name: `${ctx.pair.year % 100}.${ctx.pair.name}`},
		];

	}
	onMount( () => mounted = true );

</script>

{#if ctx.federation && ctx.standard && ctx.breeder && ctx.pair && mounted }
	<main in:fade={{duration:cfg.fadeIn}}>
		<Pair bind:pair={ctx.pair} standard={ctx.standard} user={ctx.user}/>
	</main>
{/if}