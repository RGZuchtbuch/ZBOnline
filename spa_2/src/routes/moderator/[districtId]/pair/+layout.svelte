<script>
	import {setContext} from 'svelte';

	let { children, data } = $props(); // get page

</script>

{@render children()}
