<script>
	import { getContext, setContext } from 'svelte';
	import store from '$lib/js/store.svelte.js';

	let { children } = $props(); // get page
	let federation = getContext( 'federation' );

	let districts = $state( getModeratorDistricts( store.data.user.moderates, federation ) );
	setContext( 'districts', districts );

	function getModeratorDistricts( ids, federation ) {
		let districts = [];
		if( ids && ids.length > 0 ) {
			ids.forEach( id => {
				districts.push( federation.districts[id] );
			});
		}
		return districts;
	}
</script>

{@render children()}

