<script>
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';

	import Standard from '$lib/cmp/standard/Standard.svelte';

	$effect( () => {
		setHeader( page.url );
	})

	function setHeader( page ) {
		const title = 'BDRG Rassegeflügel Standard';
		const menu = {
			trail: [
				{name: 'Start', href: '/'},
				{name: 'Standard', href: '/standard'},
			],
			options: [],
		};
		store.title = title;
		store.menu  = menu;
	}
</script>

<Standard standard={store.standard}/>

