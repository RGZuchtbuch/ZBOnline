<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import {shortName, fullName, addCrumb} from '$lib/js/tools.js';
	import {onMount} from 'svelte';
	import {page} from '$app/state';
	import Grader from '$lib/cmp/tools/Grader.svelte';

	//let { data } = $props();

	console.log( 'page grader' );

	// $effect( () => {
	// 	ctx.breeder = data.breeder;
	// });

	$effect( () => {
		if( true ) {
			setHeader();
			addCrumb( { name:'Rechner', url:page.url } );
		}
	});

	function setHeader() {
		ctx.menustate[ '/calculator' ] = page.url.href;

		ctx.title = `Rechner, Leistungsrechner`;
		ctx.submenu = [
			//{name: 'Bewertungsrechner', href: '/calculator/grader'},
			//{name: 'Abstammungsnachweis', href: '/calculator/lineageform'},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Rechner', href: '/calculator'},
			{name: 'Leistungsrechner'},
		];
		// ctx.header = {
		// 	title: `Bewertungsrechner`,
		// 	menu: {
		// 		trail: [
		// 			{name: 'Start', href: '/'},
		// 			{name: 'Rechner', href: '/calculator'},
		// 			{ name: 'Bewertungsrechner' }
		// 		],
		// 		options: [
		// 			{name: 'Abstammungsnachweis', href: '/calculator/lineageform'},				],
		// 	}
		// }

	}

</script>

<Grader />
