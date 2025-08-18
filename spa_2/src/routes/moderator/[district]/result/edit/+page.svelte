<script>
	import { onDestroy, onMount} from 'svelte';
	import { page } from '$app/state';
	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js';
	import ResultsEdit from '$lib/cmp/moderator/district/results/ResultsEdit.svelte';
	import { ArgsBuilder, activeYear } from '$lib/js/tools.js';
	import model from '$lib/js/model.js';

	// let { data } = $props();
	//
	// ctx.year = data.year;
	// ctx.group = data.group;
	// ctx.section = data.section;
	// ctx.results = data.results;
//	debugger;

	let args = $derived( getArgs( page ) )

	$effect( async () => {
		if( dirty.resultsEdit || page.url ) await loadResultsForEdit( args );
	})

	$effect( async () => {
		if( ctx.district || page.url ) setHeader();
	})

	async function loadResultsForEdit( args ) {
		console.log( 'load edit results', page.params );
		dirty.results = false;
		ctx.year = args.year || activeYear(); //query.has( 'year') ? +query.get( 'year' ) : activeYear();
		ctx.resultsEdit = null;
		ctx.resultsEdit = await model.Result.query( args );
	}

	function getArgs( page ) { // collect arguments and optionals and defaults
		let query = page.url.searchParams;
		const args = ArgsBuilder.init();
			args.district = +page.params.district;
			ArgsBuilder.setNumber( args, query, 'year', activeYear() );
			ArgsBuilder.setNumber( args, query, 'section', 3 );
			ArgsBuilder.setString( args, query, 'group', 'I' );
		return args;
	}

	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}, Daten eingeben`;
		ctx.submenu = [
			{name: 'Eingaben', href: `/moderator/${ctx.district.id}/result?year=${ctx.year}`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: ctx.district.short, href:`/moderator/${ctx.district.id}`},
			{name: 'Eingaben', href: `/moderator/${ctx.district.id}/result?year=${ctx.year}`},
			{name: 'Eingeben'},
		];

		// ctx.header = {
		// 	title: `${ctx.district.name}`,
		// 	menu: {
		// 		trail: [
		// 			{name: 'Home', href: '/'},
		// 			{name: 'Obmann', href: '/moderator'},
		// 			{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
		// 			{name: 'Eingaben', href: `/moderator/${ctx.district.id}/result?year=${ctx.year}`},
		// 			{name: 'Eingeben'},
		// 		],
		// 		options: [
		// 			{name: 'Eingaben', href: `/moderator/${ctx.district.id}/result?year=${ctx.year}`},
		// 			{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
		// 		],
		// 	},
		// };
	}

	onDestroy( () => {
		//invalidate( 'result' ); //( 'app:changed' );
	})

	console.log( 'edit page')

</script>
AA {ctx.year}
{#if ctx.district && args && ctx.resultsEdit } <!-- needed as ctx might not be updated yet -->
	<!--ResultsEdit district={ctx.district} year={ctx.year} section={ctx.section} group={ctx.group} results={ctx.results} /-->
	<ResultsEdit district={ctx.district} {args} results={ctx.resultsEdit} />
{/if}
