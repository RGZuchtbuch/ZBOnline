<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

//	import Results from '$lib/cmp/moderator/district/Results.svelte';
	import Results from '$lib/cmp/result/Results.svelte';

	let mounted = $state( false );

	$effect( async () => {
		if( dirty.results && page.url ) {
			const query = page.url.searchParams;
			const year = query.has( 'year') ? +query.get( 'year' ) : CURRENT_INPUT_YEAR;
			ctx.results = null;
			ctx.year = year;
			ctx.results = await model.Result.query( { district:+page.params.district, year:year } );
		}
	})

	$effect( () => {
		if( ctx.district && ctx.year && ctx.results ) setHeaders();
	})

	function setHeaders() {
		ctx.menustate[ '/admin' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}, Züchtermeldungen`;
		ctx.submenu = [
			{name: 'Gesamt', href:`/admin/district/${ctx.district.id}/result/district?year=${ctx.year}`},
			{name: 'Stamme', href: `/admin/district/${ctx.district.id}/result/breeder?year=${ctx.year}`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{ name:'Admin',     href:'/admin'},
			{ name:'Verbände',   href:'/admin/district'},
			{ name:'Verband',    href:`/admin/district/${ctx.district.id}`},
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
