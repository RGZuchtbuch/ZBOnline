<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
    import Report from '$lib/cmp/report/Report.svelte';
	import {addCrumb, ArgsBuilder, completedYear} from '$lib/js/tools.js';
	import model from '$lib/js/model.js';
	import {onMount} from 'svelte';

	ctx.report = null;

	$effect( async () => {
		console.log( 'LoadReport', dirty.report, page.url );
		if( dirty.report || page.url ) await loadReport( getArgs( page.url.searchParams ) );
	})

	$effect( () => {
		if (ctx.report) setHeader();
	});

	async function loadReport( args ) {
		console.log( 'Loading Report', args );
		//dirty.report = false; // hmm retriggers effect
		//ctx.report = null; //clear while waiting, no it disturbs the view
		let report = await model.Report.query( args );
		console.log( 'In load', report );
			report.args = args;
		ctx.report = report; // single trigger
	}

	function getArgs( params ) { // collect arguments and optionals and defaults
		console.log( 'Get Args', params );
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
		// ctx.header = {
		// 	title: `Zuchtleistungen`,
		// 	menu: {
		// 		trail: [
		// 			{name: 'Start', href: '/'},
		// 			{name: 'Leistungen' },
		// 		],
		// 		options: [
		// 			{name: 'Beiträge', href: '/article'},
		// 			{name: 'Verbände', href: '/federation'},
		// 			{name: 'Standard', href: '/standard'},
		// 			{name: 'Leistungen'},
		// 		],
		// 	},
		// }
	};


</script>

{#if ctx.report !== null }
	<Report	report={ctx.report}	/>
{/if}



