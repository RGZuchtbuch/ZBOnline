<script>
	import {page} from '$app/state';
	import { goto } from '$app/navigation';
	import { ctx } from '$lib/js/store.svelte.js';
	import { addCrumb } from '$lib/js/tools.js';
	import District from '$lib/cmp/moderator/District.svelte';

	let district = ctx.federation.districts[ +page.params.district ]; // ctx.district may not be known yet
	addCrumb( { name:district.short, href:page.url.href } );

	$effect( () => {
		if( ctx.district ) setHeader();
	});

	function setHeader() {
		addCrumb( page.url.href );
		ctx.header = {
			title: `${ctx.district.name}`,
			menu: {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Obmann', href: '/moderator'},
					{name: ctx.district.short},

				],
				options: [
					{name: 'Eingaben', href: `${page.url.href}/result`},
					{name: 'Züchter', href: `${page.url.href}/breeder`},
				],
			},
		}
	}

</script>

<District district={ctx.district}/>

