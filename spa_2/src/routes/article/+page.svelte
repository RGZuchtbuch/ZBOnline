<script>
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import {Article} from '$lib/js/article.svelte.js';
	import Articles from '$lib/cmp/article/Articles.svelte';


	let { data } = $props();

	let articles = $state( null )


	$effect( async ()=>{
		articles = await Article.query();
		setHeader( page.url );
	});

	function setHeader( url ) {
		const title = 'Beiträge zum BDRG Zuchtbuch';
		const menu = {
			trail : [
				{ name:'Start',  href:'/' },
				{ name:'Beiträge',   href:'/article' },
			],
			options : [],
		};
		store.title = title;
		store.menu  = menu;
	}

</script>

{#if articles}
	<Articles {articles} />
{/if}
