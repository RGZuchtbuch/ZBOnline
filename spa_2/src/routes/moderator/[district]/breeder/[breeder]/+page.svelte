<script>

	import { page } from '$app/state';
	import { goto } from '$app/navigation';
	import store from '$lib/js/store.svelte.js';
	import { txt } from '$lib/js/toolbox.js';
	import { Breeder } from '$lib/js/breeder.svelte.js';
	import { District } from '$lib/js/federation.svelte.js';
	import BreederCmp from '$lib/cmp/breeder/Breeder.svelte';

	let breeder  = $state( null );
	let district = $state( null );

	if( +page.params.breeder === 0 ) { // new
		goto( `${page.url.href}/profile`);
	} else {
		goto( `${page.url.href}/pair`);
	}

	$effect(async () => {
		breeder = await Breeder.load( +page.params.breeder, +page.params.district );
		district = await District.load( +page.params.district );
		setHeader();
	})


	function setHeader() {
		const title = breeder.id===0 ?
			'Neu' :
			`Zuchter ${breeder.firstname} ${txt(breeder.infix)} ${breeder.lastname} im ${district.name}`;
		const menu = {
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
				{name: 'Stämme', href: '/moderator/' + district.id + '/breeder/' + breeder.id + '/pair'},
				{name: 'Mitglied', href: '/moderator/' + district.id + '/breeder/' + breeder.id + '/profile'},
			],
		}
		store.title = title; // to set after loading
		store.menu  = menu;
	}

	$inspect( 'B', breeder );

</script>

{#if breeder}
	<BreederCmp {breeder} {district} />
{/if}



