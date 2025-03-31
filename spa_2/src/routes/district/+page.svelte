<script>
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';

	import Districts from '$lib/cmp/district/Districts.svelte';

	let { data } = $props();

	let federation = $state( store.federation );

	$effect( () => {
		setHeader( page.url );
	})
	//let state = getContext( 'state' )
	function setHeader( data ) {
		const title = 'Landesverbände im BDRG Zuchtbuch'
		const menu = {
			trail: [
				{name: 'Start', href: '/'},
				{name: 'Verbände', href: '/district'},
			],
			options: [],
		}
		store.title = title;
		store.menu  = menu;
	}

</script>

<Districts district={federation}/>


