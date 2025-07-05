<script>
	import { page } from '$app/state';
	import { ctx, store } from '$lib/js/store.svelte.js';
	import { txt } from '$lib/js/tools.js';
	import Pair from '$lib/cmp/pair/Pair.svelte';

	let { data } = $props();

	let district = $state( data.district );
	let breeder  = $state( data.breeder ); // make it reactive for the form etc
	let pair     = $state( data.pair );

	$effect( async () => {
		ctx.header.title = `Stamm ${pair.year}.${pair.name} von Züchter ${txt(breeder.firstname)} ${ txt(breeder.infix) } ${ txt(breeder.lastname) }`;
		ctx.header.menu = {
			trail : [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: district.short,  href:`/moderator/${pair.districtId}` },
				{name: 'Züchter', href: `/moderator/${pair.districtId}/breeder`},
//				{name:`${breeder.firstname.charAt(0)}.${breeder.lastname.charAt(0)}`, href:`/moderator/${pair.districtId}/breeder/${pair.breederId}` },
				{name:`${breeder.short}`, href:`/moderator/${pair.districtId}/breeder/${pair.breederId}` },
				{name: 'Stämme', href: `/moderator/${data.pair.districtId}/breeder/${pair.breederId}`},
				{
					name: '' + pair.year % 100 + '.' + pair.name,
					href: `/moderator/${pair.districtId}/breeder/${pair.breederId}/pair/${pair.id}`
				},
			],
			options : [],
		}
	});

</script>


<Pair {pair} />
