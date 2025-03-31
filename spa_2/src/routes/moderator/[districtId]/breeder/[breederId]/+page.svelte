<script>
	import { getContext } from 'svelte';
	import { page } from '$app/state';
	import store, { federation } from '$lib/js/store.svelte.js';
	import api from '$lib/js/api.js.obs';
	import { txt } from '$lib/js/toolbox.js';
	import Breeder from '$lib/cmp/breeder/Breeder.svelte';

	let { data } = $props();
	let breeder  = $state( data.breeder );
	let district = $state( data.district );

	$effect( () => {
		setHeader( breeder );
	})


	function setHeader() {
		const title = `Zuchter ${breeder.firstname} ${txt(breeder.infix)} ${breeder.lastname} im ${district.name}`;
		const menu = {
			trail: [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: district.short, href: `/moderator/${district.id}`},
				{name: 'Züchter', href: `/moderator/${district.id}/breeder`},
				{
					name: `${breeder.firstname.charAt(0)}.${breeder.lastname.charAt(0)}`,
					href: page.url.href,
				},
			],
			options: [
				{name: 'Stämme', href: '/moderator/' + district.id + '/breeder/' + breeder.id + '/pair'},
				{name: 'Mitglied', href: '/moderator/' + district.id + '/breeder/' + breeder.id + '/profile'},
			],
		}
		store.title.update(() => title); // to set after loading
		store.menu.update(() => menu);
	}

	$inspect( 'B', breeder );

</script>

{#if breeder}
	<Breeder {breeder} />
{/if}



