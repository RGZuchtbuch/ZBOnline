<script>
	import { onMount } from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Results from '$lib/cmp/result/Results.svelte';

	let mounted = $state( false );

	$effect( async () => {
		if( dirty.results && page.url ) {
			const query = page.url.searchParams;
			const year = query.has( 'year') ? +query.get( 'year' ) : CURRENT_INPUT_YEAR;
			ctx.year = year;
			ctx.results = await model.Result.query( { district:+page.params.district, year:year } );
		}
	})

	$effect( () => {
		if( ctx.district && ctx.year && ctx.results ) setHeaders();
	})

	function setHeaders() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.short}, Leistungen übersicht`;
		ctx.submenu = [
			{name: 'Verband', href:`/moderator/district/${ctx.district.id}/result/district?year=${ctx.year}`},
			{name: 'Zucht', href: `/moderator/district/${ctx.district.id}/result/breeder?year=${ctx.year}`},
			{name: 'Stämme', href: `/moderator/district/${ctx.district.id}/result/pair?year=${ctx.year}`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{ name:'Obmann',     href:'/moderator'},
			{ name:'Verbände',   href:'/moderator/district'},
			{ name:'Verband',    href:`/moderator/district/${ctx.district.id}`},
			{ name:'Leistungen'}
		];
	}
	onMount( () => { mounted = true })

</script>


{#if ctx.district && ctx.results && mounted }

	<main class='' in:fade={{duration:cfg.fadeIn}}>
		<Results district={ctx.district} year={ctx.year} results={ctx.results} />
	</main>
{/if}
