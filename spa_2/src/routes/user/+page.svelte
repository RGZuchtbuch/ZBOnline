<script>
	import { getContext } from 'svelte';
    import store from '$lib/js/store.svelte.js';
    import User from '$lib/cmp/user/User.svelte';

    //let state = getContext( 'state' );

	const title = 'Benutzer im RGZuchtbuch';
	const menu = {
		trail : [
			{ name:'Start',    href:'/' },
			{ name:'Züchter',  href:'/user' },
		],
		options : [],
	};

	store.title.update( () => title );
	store.menu.update( () => menu );


</script>

<User />


