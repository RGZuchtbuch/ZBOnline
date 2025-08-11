<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import Article from '$lib/cmp/article/Article.svelte';
	import {addCrumb, fullName, shortName} from '$lib/js/tools.js';

 	$effect( async () => {
		setHeader();
		addCrumb( { name:'Züchter', url:page.url } );
 	});

	function setHeader() {
		ctx.menustate[ '/calculator' ] = page.url.href;

		ctx.title = `Leistungs- und Abstammungsnachweis Rechner`;
		ctx.submenu = [
			{name: 'Leistungsrechner', href: '/calculator/grader'},
			{name: 'Abstammungsnachweis', href: '/calculator/lineage'},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Rechner'},
		];
		// const header = { // first in local to avoid retriggering when assigning
		// 	title : 'Züchter im Zuchtbuch',
		// 	menu : {
		// 		trail: [
		// 			{name: 'Start', href: '/'},
		// 			{name: 'Rechner'},
		// 		],
		// 		options: [
		// 			{name: 'Bewertungsrechner', href: '/calculator/grader'},
		// 			{name: 'Abstammungsnachweis', href: '/calculator/lineageform'},
		// 		],
		// 	},
		// };
		// // if( ctx.user ) {
		// // 	header.menu.options.push( {name: 'Mein Zuchtbuch', href: `/breeder/me/${ctx.user.id}`} );
		// // }
		// ctx.header = header; // triggers
	}


</script>

<h2>Breeder page</h2>
<ul class=' pl-2'>

	<li>Info's zum Züchter</li>
	<li>Bewertungsrechner</li>
	<li>Abstammungsnachweis</li>
</ul>



<style>
	ul {
		@apply list-inside list-disc text-red-600;
	}
</style>