<script>
	import { fade } from 'svelte/transition';
	import { page } from '$app/state';
	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js';
	import { addCrumb, fullName, shortName } from '$lib/js/tools.js';
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
		ctx.pair = id === 0 ?
			await model.Pair.new( ctx.breeder) : // new for this breeder
			await model.Pair.load( id );
		console.log('Loaded pair');
	}

	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.short} : Züchter ${fullName(ctx.breeder)}, Stämm ${ctx.pair.year % 100}.${ctx.pair.name}`;
		ctx.submenu = [
//			{name: 'Stämme', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/pair`},
//			{name: 'Mitglied', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/profile`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
			{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
			{name: `${shortName(ctx.breeder)}`, href:`/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}`},
			{name: 'Stämme', href: `/moderator/${ctx.pair.districtId}/breeder/${ctx.pair.breederId}/pair`},
			{name: `${ctx.pair.year % 100}.${ctx.pair.name}`},
		];

	}
	onMount( () => mounted = true );

</script>

{#if ctx.federation && ctx.standard && ctx.breeder && ctx.pair && mounted }
	<main in:fade={{duration:cfg.fadeIn}}>
	<Pair pair={ctx.pair} />
	</main>
{/if}