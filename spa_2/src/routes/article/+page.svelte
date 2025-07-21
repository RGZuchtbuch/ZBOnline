<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import Articles from '$lib/cmp/article/Articles.svelte';


	let { data } = $props();

	ctx.articles = data.articles;
	$effect( () => {
		ctx.articles = data.articles;
	});

	$effect( async ()=>{
		ctx.header = {
			title : `Beiträge zum BDRG Zuchtbuch [${data.articles.length}]`,
			menu : {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Beiträge', href: '/article'},
				],
				options: [
					{name: 'Verbände', href: '/federation'},
					{name: 'Standard', href: '/standard'},
					{name: 'Leistungen', href: '/report'},
				],
			}
		};
	});

</script>

{#if ctx.articles}
	<Articles articles={data.articles} />
{/if}