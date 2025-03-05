<script>
	import { getContext } from 'svelte';
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import Breeder from '$lib/cmp/breeder/Breeder.svelte';

	let { data } = $props();
	let state = getContext( 'state' );

	const path = page.url.pathname;


	state.title = `Zuchter ${data.breeder.firstname} ${data.breeder.infix} ${data.breeder.lastname} im ${data.district.name}`;
	state.menu = {
		trail : [
			{name: 'Home', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: data.district.short, href: '/moderator/' + data.district.id},
			{name: 'Züchter', href: '/moderator/' + data.district.id + '/breeder'},
			{
				name: `${data.breeder.firstname.charAt(0)}.${data.breeder.lastname.charAt(0)}`,
				href: '/moderator/' + data.district.id + '/breeder/' + data.breeder.id
			},
		],
		options : [
			{name: 'Stämme', href: '/moderator/' + data.district.id + '/breeder/' + data.breeder.id + '/pair'},
			{name: 'Mitglied', href: '/moderator/' + data.district.id + '/breeder/' + data.breeder.id + '/profile'},
		],
	}

	console.log( 'Results page', path );

</script>

{#if data.breeder}
	<Breeder {data} />
{/if}



