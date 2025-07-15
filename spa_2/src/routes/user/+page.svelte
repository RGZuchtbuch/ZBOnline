<script>
	import { getContext } from 'svelte';
    import { ctx } from '$lib/js/store.svelte.js';
    import User from '$lib/cmp/user/User.svelte';

	let { data } = $props();

	//$effect( () => {
		const header = {
			title : 'Benutzer im RGZuchtbuch',
			menu : {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Anmeldung', href: '/user'},
				],
				options: [],
			},
		};
		ctx.header = header;
	//})
</script>

<User />


