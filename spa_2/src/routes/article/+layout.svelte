<script>
    import store from '$lib/js/store.svelte.js';
	import {setContext} from 'svelte';

    let { children, data } = $props(); // get page

	let articles = $state( data.articles );
    setContext( 'articles', articles );
</script>

{@render children()}
