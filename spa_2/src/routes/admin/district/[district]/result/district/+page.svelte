<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';

	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js';
	import { ArgsBuilder } from '$lib/js/tools.js';
	import model from '$lib/js/model.js';

//	import ResultsEdit from '$lib/cmp/moderator/district/results/ResultsEdit.svelte';
	import District from '$lib/cmp/result/District.svelte';

	let mounted = $state( false );

	let args = $derived( getArgs( page ) )

	$effect( async () => {
		if( dirty.resultsEdit && page.url ) await loadResultsForEdit( args );
	})

	$effect( async () => {
		if( ctx.district && page.url ) setHeader();
	})

	async function loadResultsForEdit( args ) {
		//ctx.year = args.year || activeYear(); //query.has( 'year') ? +query.get( 'year' ) : activeYear();
		ctx.resultsEdit = null;
		ctx.resultsEdit = await model.Result.query( args );
	}

	function getArgs( page ) { // collect arguments and optionals and defaults
		let query = page.url.searchParams;
		const args = ArgsBuilder.init();
			args.district = +page.params.district;
			ArgsBuilder.setNumber( args, query, 'year', CURRENT_INPUT_YEAR );
			ArgsBuilder.setNumber( args, query, 'section', 3 );
			ArgsBuilder.setNumber( args, query, 'breed', null );
			ArgsBuilder.setNumber( args, query, 'color', null );
			ArgsBuilder.setString( args, query, 'group', 'I' );
		return args;
	}

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}, Daten eingeben`;
		ctx.submenu = [
//			{name: 'Eingaben', href: `/moderator/district/${ctx.district.id}/result?year=${ctx.year}`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Admin', href: '/admin'},
			{name: 'Verbände', href: '/admin/district'},
			{name: 'Verband', href:`/admin/district/${ctx.district.id}`},
			{name: 'Leistungen', href: `/admin/district/${ctx.district.id}/result?year=${ctx.year}`},
			{name: 'Gesamt'},
		];
	}

	onMount( () => { mounted = true })

</script>


{#if ctx.district && args && ctx.resultsEdit && mounted } <!-- needed as ctx might not be updated yet -->
	<main class='' in:fade={{duration:cfg.fadeIn}}>
		<District district={ctx.district} {args} bind:results={ctx.resultsEdit} />
	</main>
{/if}


<style>

</style>