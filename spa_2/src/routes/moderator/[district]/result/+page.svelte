<script>
	//import { invalidate } from '$app/navigation';
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { activeYear, addCrumb } from '$lib/js/tools.js';
	import model from '$lib/js/model.js';
	import Results from '$lib/cmp/moderator/district/Results.svelte';

	$effect( () => { console.log( 'results: page.url', page.url )} );

	$effect( async () => {
		if( dirty.results || page.url ) await loadResults( page.params, page.url.searchParams );
	})

	$effect( () => {
		if( ctx.district && ctx.year && ctx.results ) setHeaders();
	})

	async function loadResults( params, query ) {
		dirty.results = false;
		ctx.year = query.has( 'year') ? +query.get( 'year' ) : activeYear();
		ctx.results = null;
		ctx.results = await model.Result.query( { district:+params.district, year:ctx.year } );
	}

	function setHeaders() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}, Eingaben`;
		ctx.submenu = [
			{name: 'Eingeben', href: `/moderator/${ctx.district.id}/result/edit?year=${ctx.year}`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: ctx.district.short, href:`/moderator/${ctx.district.id}`},
			{name: 'Eingaben' },
		];

		// ctx.header = {
		// 	title: `Eingaben für ${ctx.district.name} ${ctx.year}`,
		// 	menu: {
		// 		trail: [
		// 			{name: 'Home', href: '/'},
		// 			{name: 'Obmann', href: '/moderator'},
		// 			{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
		// 			{name: 'Eingaben'},
		// 		],
		// 		options: [
		// 			{name: 'Leistungen', href:`/moderator/${ctx.district.id}/report?year=${ctx.year}` },
		// 			{name: 'Eingaben' },
		// 			{name: 'Eingeben', href: `/moderator/${ctx.district.id}/result/edit`},
		// 			{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
		// 		],
		// 	}
		// }
	}

</script>

{#if ctx.district!==null && ctx.year!==null && ctx.results!==null}
	<Results district={ctx.district} year={ctx.year} results={ctx.results} />
{/if}
