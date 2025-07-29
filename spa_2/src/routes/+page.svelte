<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js'
	import model from '$lib/js/model.js';
	import { addCrumb } from '$lib/js/tools.js';

	import Article from '$lib/cmp/article/Article.svelte';

	addCrumb( { name:'Start', url:page.url } );

	$effect( async () => {
		if( true ) await loadArticle( 1 );
	})
	$effect( () => {
		if( true ) ctx.header = setHeader();
	})

	async function loadArticle( id ) {
		console.log("load Article", id )
		dirty.article = false;
		ctx.article = null;
		ctx.article = await model.Article.load( id );
	}

	function setHeader() {
		const header = {
			title: 'Das BDRG Zuchtbuch',
			menu: {
				trail: [
					{name: 'Start', href: '/'},
				],
				options: [
					{name: 'Beiträge', href: '/article'},
					{name: 'Verbände', href: '/federation'},
					{name: 'Standard', href: '/standard'},
					{name: 'Leistungen', href: '/report'},
					{name: 'Züchter', href: '/breeder'},
				],
			},
		};
		if ( ctx.user ) { // restricted
			if ( ctx.user.moderator) header.menu.options.push({name: 'Obmann', href: '/moderator'});
			if ( ctx.user.admin) header.menu.options.push({name: 'Admin', href: '/admin'});
		}
		return header;
	}



</script>

<Article article={ctx.article}/>

