<script>
	import { page } from '$app/state';
	import { ctx, store } from '$lib/js/store.svelte.js';
	import Article from '$lib/cmp/article/Article.svelte';

	let { data } = $props();

	$effect( async () => {

	})
	//ctx.article = $derived( data.article );

 	$effect( async () => {
		const header = {
			title : 'Züchter im Zuchtbuch',
			menu : {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Züchter'},
				],
				options: [
					{name: 'Beiträge', href: '/article'},
					{name: 'Verbände', href: '/federation'},
					{name: 'Standard', href: '/standard'},
					{name: 'Leistungen', href: '/report'},
				],
			},
		};

		if( ctx.user ) {
			header.menu.options.push( {name: 'Mein Zuchtbuch', href: `/breeder/${ctx.user.id}`} );
		}

		ctx.header = header;

 	});


</script>


Breeder

* Bewertungsrechner
* Abstammungsnachweis