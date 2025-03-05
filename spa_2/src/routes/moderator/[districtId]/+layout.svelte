<script>
	import { setContext} from 'svelte';

	let { children, data } = $props();

	const district = $state( data.district );
	setContext( 'district', district );
</script>



{@render children()}