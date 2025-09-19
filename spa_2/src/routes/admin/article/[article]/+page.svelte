<script>
	import { page } from '$app/state';

	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Article from '$lib/cmp/admin/article/Article.svelte';

	$effect( async () => {
		//if ( page.url ) loadArticle( +page.params.article );
		loadArticle( +page.params.article );
	});
	$effect( () => {
		if( ctx.article ) setHeader();
	})

	async function loadArticle( id ) {
		ctx.article = await model.Article.load( id );
	}

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;

		ctx.title = `Admin: ${ctx.article.title ? ctx.article.title : '?'}`;
		ctx.submenu = [];
		ctx.crumbs = [
			{ name:'Admin',    href:'/admin' },
			{name: 'Infos', href: `${ page.url.pathname.substring( 0, page.url.pathname.lastIndexOf( "/") ) }` },//'/article'},
			{name: ctx.article.title ? ctx.article.title.substring( 0, 20 )+'..' : '?' },
		];
	}

</script>

{#if ctx.article}
	<Article bind:article={ctx.article} />
{/if}