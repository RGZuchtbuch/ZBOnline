<script>

	import { page } from '$app/state';
	import { goto } from '$app/navigation';
	import { ctx } from '$lib/js/store.svelte.js';
	import { txt } from '$lib/js/tools.js';
	import model from '$lib/js/model.js';

	//import Breeder from '$lib/cmp/breeder/Breeder.svelte';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';

	let { data } = $props();

	console.log( 'B', data.breeder );

	const district = $state( data.district )
	const breeder = $state( data.breeder );
	const year = $state( data.year );


	// if( +page.params.breeder === 0 ) { // new
	// 	goto( `${page.url.href}/profile`);
	// } else {
	// 	goto( `${page.url.href}/pair`);
	// }

	$effect(async () => {
		setHeader( data.breeder, data.district );
	})


	function setHeader( breeder, district ) {
		ctx.header.title =
			breeder.id===0 ?
			'Neu' :
			`Zuchter ${breeder.firstname} ${txt(breeder.infix)} ${breeder.lastname} im ${district.name}`;

		ctx.header.menu = {
			trail: [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: district.short, href: `/moderator/${district.id}`},
				{name: 'Züchter', href: `/moderator/${district.id}/breeder`},
				{
					name: breeder.id===0 ?
						'Neu' :
						`${breeder.firstname.charAt(0)}.${breeder.lastname.charAt(0)}`,
					href: page.url.href,
				},
			],
			options: [
//				{name: 'Stämme', href: '/moderator/' + district.id + '/breeder/' + breeder.id + '/pair'},
				{name: 'Mitglied', href: '/moderator/' + district.id + '/breeder/' + breeder.id + '/profile'},
			],
		}
	}

</script>

{#if data.breeder && data.pairs}
	<Pairs breeder={data.breeder} district={data.district} pairs={data.pairs} year={data.year} />
{/if}



