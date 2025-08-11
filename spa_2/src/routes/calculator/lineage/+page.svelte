<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import {shortName, fullName, addCrumb} from '$lib/js/tools.js';
	import {onMount} from 'svelte';
	import {page} from '$app/state';
	import Lineage from '$lib/cmp/tools/Lineage.svelte';

	//let { data } = $props();

	console.log( 'Lineage' );

	// $effect( () => {
	// 	ctx.breeder = data.breeder;
	// });

	$effect( () => {
		if( true ) {
			setHeader();
			addCrumb( { name:'Abstammungsnachweis', url:page.url } );
		}
	});

	function setHeader() {
		ctx.menustate[ '/calculator' ] = page.url.href;

		ctx.title = `Rechner, Abstammungsnachweis`;
		ctx.submenu = [
			//{name: 'Bewertungsrechner', href: '/calculator/grader'},
			//{name: 'Abstammungsnachweis', href: '/calculator/lineageform'},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Rechner', href: '/calculator'},
			{name: 'Abstammungsnachweis'},
		];
		// ctx.header = {
		// 	title: `Abstammungsnachweis zur Zuchtbuchschau`,
		// 	menu: {
		// 		trail: [
		// 			{name: 'Start', href: '/'},
		// 			{name: 'Rechner', href: '/calculator'},
		// 			{name: 'Abstammungsnachweis' },
		// 		],
		// 		options: [
		// 			{name: 'Bewertungsrechner', href: '/calculator/grader'},
		// 		],
		// 	}
		// }

	}

</script>

<Lineage />
