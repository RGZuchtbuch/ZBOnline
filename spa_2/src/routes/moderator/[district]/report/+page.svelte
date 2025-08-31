<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
	import { ArgsBuilder, completedYear} from '$lib/js/tools.js';
	import model from '$lib/js/model.js';
	import Table from '$lib/cmp/report/view/Table.svelte';

	ctx.report = null;

	$effect( async () => {
		if( dirty.report && page.url ) await loadReport( getArgs( page.url.searchParams ) );
	})

	$effect( () => {
		if (ctx.report) setHeader();
	});

	async function loadReport( args ) {
		//dirty.report = false;
		//ctx.report = null; //clear while waiting
		let report = await model.Report.loadTable( args );
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
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}, Zuchtleistungen`;
		ctx.submenu = [
			{name: 'Eingaben', href: `/moderator/${ctx.district.id}/result?year=${ctx.year}`},
			{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
		];
		ctx.crumbs = [
			{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: ctx.district.short, href:`/moderator/${ctx.district.id}`},
			{name: 'Leistungen' },
		];
	}


</script>

{#if ctx.report !== null }
	<main class='flex flex-col break-after-page' in:fade={{duration:cfg.fadeIn}}>
		<h2 class='text-center'>Leistungsdaten im {ctx.district.name} für {ctx.report.args.year}</h2>
		<p>Das Jahr past sich das Jahr der Eingaben an !</p>
		<div class='flex flex-col py-4'>
			<Table table={ctx.report} district={ctx.district} year={ctx.report.args.year} />
		</div>
	</main>
{/if}



