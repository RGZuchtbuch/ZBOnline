<script>
	import { onMount } from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';
	import Report from '$lib/cmp/report/Report.svelte';

	let mounted = $state( false );

	let district = $derived( ctx.federation.districts[ page.url.searchParams.has( 'district' ) ? +page.url.searchParams.get( 'district' ) : 1 ] );
	let year     = $derived( page.url.searchParams.has( 'year' ) ? +page.url.searchParams.get( 'year' ) : CURRENT_REPORT_YEAR );
	let group    = $derived( page.url.searchParams.has( 'group' ) ? page.url.searchParams.get( 'group' ) : null );

	let section  = $derived( page.url.searchParams.has( 'section' ) ? ctx.standard.sections[ +page.url.searchParams.get( 'section' ) ] : null );
	let breed    = $derived( page.url.searchParams.has( 'breed' ) ? ctx.standard.breeds[ +page.url.searchParams.get( 'breed' ) ] : null );
	let color    = $derived( page.url.searchParams.has( 'color' ) ? ctx.standard.colors[ +page.url.searchParams.get( 'color' ) ] : null );

	let chart = $state.raw( null ); // raw to avoid needles nested reactivity, does it matter for performance ?
	let map   = $state.raw( null );
	let trend = $state.raw( null );
	let table = $state.raw( null );

	let args = $derived( { district:district?district.id:null, year:year, group:group, section:section?section.id:null, breed:breed?breed.id:null, color:color?color.id:null } );

	$effect( async () => chart = await model.Report.query( { target:'chart', district:district.id, year:year, group:group, section:section?section.id:null, breed:breed?breed.id:null, color:color?color.id:null } ) );
	$effect( async () => map = await model.Report.query( { target:'map', year:year, group:group, section:section?section.id:null, breed:breed?breed.id:null, color:color?color.id:null } ) );
	$effect( async () => trend = await model.Report.query( { target:'trend', district:district.id, group:group, section:section?section.id:null, breed:breed?breed.id:null, color:color?color.id:null } ) );
	$effect( async () => table = await model.Report.query( { target:'table', district:district.id, year:year, group:group } ) );


	$effect( () => {
		if (page.url) setHeader();
	});

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

{#if mounted}
	<main class='' in:fade={{duration:cfg.fadeIn}}>
		<Report {district} {year} {group} {section} {breed} {color} {chart} {map} {trend} {table} />
	</main>
{/if}



