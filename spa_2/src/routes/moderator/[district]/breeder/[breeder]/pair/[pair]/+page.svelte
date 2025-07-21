<script>
	import { page } from '$app/state';
	import { ctx, store } from '$lib/js/store.svelte.js';
	import { fullName, txt } from '$lib/js/tools.js';
	import Pair from '$lib/cmp/pair/Pair.svelte';

	let { data } = $props();

	ctx.pair = data.pair;

	//let district = $state( data.district );
	//let breeder  = $state( data.breeder ); // make it reactive for the form etc
	//let pair     = $state( data.pair );
	// ctx.breeder = data.breeder;
	// ctx.district = data.district;
	// ctx.pair = data.pair;
	$effect( () => {
		// ctx.breeder = data.breeder;
		// ctx.district = data.district;
		// ctx.pair = data.pair;
	})

	$effect( async () => {
		ctx.header.title = `Stamm ${data.pair.year}.${data.pair.name} von Züchter ${fullName(data.breeder) }`;
		ctx.header.menu = {
			trail : [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: data.district.short,  href:`/moderator/${data.pair.districtId}` },
				{name: 'Züchter', href: `/moderator/${data.pair.districtId}/breeder`},
//				{name:`${breeder.firstname.charAt(0)}.${breeder.lastname.charAt(0)}`, href:`/moderator/${pair.districtId}/breeder/${pair.breederId}` },
				{name:`${data.breeder.short}`, href:`/moderator/${data.pair.districtId}/breeder/$ctx.{pair.breederId}` },
				{name: 'Stämme', href: `/moderator/${data.pair.districtId}/breeder/${data.pair.breederId}/pair`},
				{name: `${data.pair.year % 100}.${data.pair.name}` },
			],
			options : [],
		}
	});

</script>


<Pair pair={ctx.pair} />
