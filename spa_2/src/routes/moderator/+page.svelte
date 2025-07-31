<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { addCrumb } from '$lib/js/tools.js';
	import Districts from '$lib/cmp/moderator/Districts.svelte';

	$effect( () => {
		ctx.header = {
			title: 'Obmann: Verbände zum Verwalten',
			menu: {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Obmann'},
				],
				options: [],
			},
		}
	});

</script>

<Districts districts={ctx.districts}/>



