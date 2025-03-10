<script>
	import { getContext } from 'svelte';
    import ModeratorDistricts from '$lib/cmp/moderator/Districts.svelte';
	import {goto} from '$app/navigation';
	import store from '$lib/js/store.svelte.js';

	//let districts = getContext( 'districts' );
	//let state     = getContext( 'state' );

	const title = 'Obmann';
	const menu = {
		trail : [
			{ name:'Start',    href:'/' },
			{ name:'Obmann',   href:'/moderator' },
		],
	    options : [],
	};

	store.title.update( () => title );
	store.menu.update( () => menu );


</script>

<ModeratorDistricts />



