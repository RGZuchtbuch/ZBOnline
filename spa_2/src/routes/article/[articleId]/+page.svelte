<script>
	import { getContext } from 'svelte';

	import store, { user } from '$lib/js/store.svelte.js';
	import Article from '$lib/cmp/article/Article.svelte';
	import { page } from '$app/state';
	import api from '$lib/js/api.js';

	let article = $state( { title:'laden...' } );

	//let app = getContext( 'state' );

	$effect( () => {
		load( page );
	})


	async function load ( page ) {
		const articleId = +page.params.articleId;
		console.log( 'Aid', articleId );
		if( articleId === 0 ) { // new article
			article = { id:0, title:'Todo', author:$user.firstname, html:'Todo' };
		} else { // fetch article by id
			console.log( 'Existing', articleId )
			const response = await api.article.get(articleId);
			if (response && response.article) {
				//store.article.update(() => response.article);
				article = response.article;
			} else { // not found
				article = { id:0, title:'Unbekannter Beitrag !', author:null, html:'...' };
			}
		}
		setHeader();
	}

	function setHeader() {
		if( article ) {
			const title = null; //article.title;
			const menu = {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Info', href: '/article'},
					{name: 'Beitrag', href: page.url.href},
				],
				options: [],
			}

			store.title.update(() => title); // to set after loading
			store.menu.update(() => menu);
		}
	}


</script>

<Article bind:article={article}/>
