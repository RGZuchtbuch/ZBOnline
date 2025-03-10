<script>
	import store from '$lib/js/store.svelte.js';
	import { getContext, setContext } from 'svelte';

	let { children, data } = $props(); // get page

</script>

{@render children()}

