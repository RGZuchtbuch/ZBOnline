<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
	import { activeYear, addCrumb } from '$lib/js/tools.js';
	import model from '$lib/js/model.js';

	import Results from '$lib/cmp/moderator/district/Results.svelte';

	let mounted = $state( false );

	$effect( async () => {
		if( dirty.results && page.url ) {
			const query = page.url.searchParams;
			const year = query.has( 'year') ? +query.get( 'year' ) : activeYear();
			ctx.year = year;
			ctx.results = await model.Result.query( { district:+page.params.district, year:year } );
		}
	})

	$effect( () => {
		if( ctx.district && ctx.year && ctx.results ) setHeaders();
	})

	function setHeaders() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}, Eingaben`;
		ctx.submenu = [
			{name: 'Verbandsmeldung Eingeben', href:`/moderator/${ctx.district.id}/result/edit?year=${ctx.year}`},
			{name: 'Züchtermeldung eingeben', href: `/moderator/${ctx.district.id}/breeder`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: ctx.district.short, href:`/moderator/${ctx.district.id}`},
			{name: 'Leistungen' },
		];
	}
	onMount( () => { mounted = true })

</script>

{ctx.year}
{#if ctx.district && ctx.results && mounted }

	<main class='' in:fade={{duration:cfg.fadeIn}}>
		<Results district={ctx.district} year={ctx.year} results={ctx.results} />
	</main>
{/if}
