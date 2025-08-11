<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Articles from '$lib/cmp/article/Articles.svelte';
	import {addCrumb} from '$lib/js/tools.js';


	addCrumb( { name:'Beitrag', url:page.url } );

	$effect( async () => {
		if ( dirty.articles || page.url ) await loadArticles();
	})

	$effect( async () => {
		if( ctx.articles ) setHeader();
	})

	async function loadArticles() {
		console.log( 'Load Articles' );
		dirty.articles = false;
		ctx.articles = null;
		ctx.articles = await model.Article.query();
	}

	function setHeader() {
		//ctx.menustate[ '/article' ] = page.url.href;
		ctx.title = `Beiträge zum BDRG Zuchtbuch [${ctx.articles.length}]`;
		ctx.submenu = [];
		ctx.crumbs = [
			{name: 'Start', href: '/'},
			{name: 'Infos', href: '/article'},
		];
		// ctx.header = {
		// 	title : `Beiträge zum BDRG Zuchtbuch [${ctx.articles.length}]`,
		// 	menu : {
		// 		trail: [
		// 			{name: 'Start', href: '/'},
		// 			{name: 'Beiträge', href: '/article'},
		// 		],
		// 		options: [
		// 			{name: 'Beiträge', href: '/article'},
		// 			{name: 'Verbände', href: '/federation'},
		// 			{name: 'Standard', href: '/standard'},
		// 			{name: 'Leistungen', href: '/report'},
		// 		],
		// 	}
		// };
	}

</script>

<Articles articles={ctx.articles} />
