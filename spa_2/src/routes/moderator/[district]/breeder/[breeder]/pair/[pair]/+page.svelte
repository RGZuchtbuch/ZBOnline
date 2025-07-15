<script>
	import { page } from '$app/state';
	import { ctx, store } from '$lib/js/store.svelte.js';
	import { txt } from '$lib/js/tools.js';
	import Pair from '$lib/cmp/pair/Pair.svelte';

	let { data } = $props();

	//let district = $state( data.district );
	//let breeder  = $state( data.breeder ); // make it reactive for the form etc
	//let pair     = $state( data.pair );

	$effect( () => {
		ctx.breeder = data.breeder;
		ctx.district = data.district;
		ctx.pair = data.pair;
	})

	$effect( async () => {
		ctx.header.title = `Stamm ${ctx.pair.year}.${ctx.pair.name} von Züchter ${txt(ctx.breeder.firstname)} ${ txt(ctx.breeder.infix) } ${ txt(ctx.breeder.lastname) }`;
		ctx.header.menu = {
			trail : [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: ctx.district.short,  href:`/moderator/${ctx.pair.districtId}` },
				{name: 'Züchter', href: `/moderator/${ctx.pair.districtId}/breeder`},
//				{name:`${breeder.firstname.charAt(0)}.${breeder.lastname.charAt(0)}`, href:`/moderator/${pair.districtId}/breeder/${pair.breederId}` },
				{name:`${ctx.breeder.short}`, href:`/moderator/${ctx.pair.districtId}/breeder/$ctx.{pair.breederId}` },
				{name: 'Stämme', href: `/moderator/${ctx.pair.districtId}/breeder/${ctx.pair.breederId}/pair`},
				{name: `${ctx.pair.year % 100}.${ctx.pair.name}` },
			],
			options : [],
		}
	});

</script>


<Pair pair={ctx.pair} />
