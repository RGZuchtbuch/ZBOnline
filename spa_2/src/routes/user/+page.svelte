<script>
    import { app } from '$lib/js/store.svelte.js';
    import User from '$lib/cmp/user/User.svelte';

    let { data } = $props();

	app.menu.title = 'Benutzer im RGZuchtbuch';
	app.menu.trail = [
        { name:'Start',    href:'/' },
        { name:'Benützer', href:'/user' },
    ];
	app.menu.options = [];

    console.log( 'User page' );

</script>

<User />


