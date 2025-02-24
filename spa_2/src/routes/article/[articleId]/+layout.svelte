<script>
	import { setContext } from 'svelte';

	let { children, data } = $props();
	let article = $state( data.article );

	setContext( 'article', article );
</script>

{@render children()}