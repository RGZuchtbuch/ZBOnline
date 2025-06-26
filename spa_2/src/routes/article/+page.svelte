<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import Articles from '$lib/cmp/article/Articles.svelte';


//	let { data } = $props();

	$effect( async ()=>{
		console.log( 'Article set header' );
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
		ctx.header.title = title;
		ctx.header.menu  = menu;
	}

</script>
G
{#if ctx.articles}
	<Articles articles={ctx.articles} />
{/if}
