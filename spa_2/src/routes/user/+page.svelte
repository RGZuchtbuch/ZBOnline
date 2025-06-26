<script>
	import { getContext } from 'svelte';
    import store from '$lib/js/store.svelte.js';
    import User from '$lib/cmp/user/User.svelte';

    //let state = getContext( 'state' );
	let ctx = getContext( 'ctx' );

	$effect( () => {
		setHeader()
	})

	function setHeader() {
		const title = 'Benutzer im RGZuchtbuch';
		const menu = {
			trail: [
				{name: 'Start', href: '/'},
				{name: 'Züchter', href: '/user'},
			],
			options: [],
		};
		ctx.header.title = title;
		ctx.header.menu = menu;
	}
</script>

<User />


