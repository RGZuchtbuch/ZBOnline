<script>
	import { getContext } from 'svelte';
    import store from '$lib/js/store.svelte.js';
    import User from '$lib/cmp/user/User.svelte';

    let state = getContext( 'state' );

	state.title = 'Benutzer im RGZuchtbuch';
	state.menu = {
		trail : [
			{ name:'Start',    href:'/' },
			{ name:'Benützer', href:'/user' },
		],
		options : [],
	};


</script>

<User />


