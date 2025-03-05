<script>
	import store from '$lib/js/store.svelte.js';
	import { getContext, setContext } from 'svelte';

	let { children, data } = $props(); // get page

	const districts = $state( getModeratorDistricts( store.user.moderator, data.federation ) );

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

