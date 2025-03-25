<script>
	//import { getContext } from 'svelte';
	import store, { federation } from '$lib/js/store.svelte.js';

	import Districts from '$lib/cmp/district/Districts.svelte';

	let { data } = $props();

	$effect( () => {
		setHeader( data );
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

		store.title.update(() => title);
		store.menu.update(() => menu);
		data.title = title;
	}

</script>

<Districts district={data.federation.root}/>


