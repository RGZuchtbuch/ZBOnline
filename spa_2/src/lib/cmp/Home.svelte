<script>
	import {onMount} from 'svelte';
	import { fade } from 'svelte/transition';

	import { cfg } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Article from '$lib/cmp/article/Article.svelte';

	let welcome = $state( null );
	let news = $state( null );

	onMount( () => {
		Promise.all( [
			model.Article.load( 2 ), model.Article.load( 3 ),
		]).then( ( values ) => {
			welcome = values[0];
			news    = values[1];
		});
	});

</script>

<div class='p-6' in:fade={{duration:cfg.fadeIn}}>
	<Article article={welcome}/>
	<hr class=''>
	<Article article={news}/>
</div>

