<script>
	import { getContext } from 'svelte';
	import Standard from '$lib/cmp/standard/Standard.svelte';

	let standard = getContext( 'standard' );
	let page = getContext( 'page' )

	page.title = 'Die anerkannten Rassen im BDRG';
	page.menu = {
		trail : [
			{ name:'Start',      href:'/' },
			{ name:'Standard',   href:'/standard' },
		],
		options : [],
	};

</script>

<Standard standard={standard} />

