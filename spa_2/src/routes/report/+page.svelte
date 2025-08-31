<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
    import Report from '$lib/cmp/report/Report.svelte';
	import { ArgsBuilder, completedYear } from '$lib/js/tools.js';
	import model from '$lib/js/model.js';
	import {onMount} from 'svelte';

	let mounted = $state( false );

	$effect( async () => {
		if( dirty.report && page.url ) await loadReport( getArgs( page.url.searchParams ) );
	})

	$effect( () => {
		if (ctx.report) setHeader();
	});

	async function loadReport( args ) {
		console.log('Load report', args)
		let report = await model.Report.query( args );
		report.args = args;
		ctx.report = report; // single trigger
	}

	function getArgs( params ) { // collect arguments and optionals and defaults
		const args = ArgsBuilder.init();
			ArgsBuilder.setNumber( args, params, 'district', 1 );
			ArgsBuilder.setNumber( args, params, 'year', completedYear() );

			ArgsBuilder.setString( args, params, 'group' );
			ArgsBuilder.setNumber( args, params, 'section' );
			ArgsBuilder.setNumber( args, params, 'breed' );
			ArgsBuilder.setNumber( args, params, 'color' );
			ArgsBuilder.setNumber( args, params, 'type', 2 );
		return args;
	}


	function setHeader() {
		ctx.menustate[ '/report' ] = page.url.href;
		ctx.title = `Leistungen im Zuchtbuch`;
		ctx.submenu = [];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Leistungen', href: '/report'},
		];
	}

	onMount( () => mounted = true );

</script>

{#if ctx.report && mounted}
	<main class='' in:fade={{duration:cfg.fadeIn}}>
		<Report	report={ctx.report}	/>
	</main>
{/if}



