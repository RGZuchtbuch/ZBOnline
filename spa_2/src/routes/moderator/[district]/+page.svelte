<script>
	import {page} from '$app/state';
	import { goto } from '$app/navigation';

	import { ctx } from '$lib/js/store.svelte.js';
	import District from '$lib/cmp/moderator/District.svelte';

	let { data } = $props();
	$effect( () => {
		ctx.district = data.district;
	}); // in context to avoid warnings on wrong updates.

	$effect( async () => {
		ctx.header = {
			title: `${data.district.name}`,
			menu: {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Obmann', href: '/moderator'},
					{name: data.district.short},

				],
				options: [
					{name: 'Eingaben', href: `${page.url.href}/result`},
					{name: 'Züchter', href: `${page.url.href}/breeder`},
				],
			},
		}
	});

</script>

<District district={ctx.district}/>

