<script>
	import {setContext} from 'svelte';

	let { children, data } = $props(); // get page

	let pair = $state( data.pair );
	setContext( 'pair', pair );
</script>

{@render children()}
