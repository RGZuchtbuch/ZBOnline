<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';
	//import Breeder from '$lib/cmp/breeder/Breeder.svelte';

	let { data } = $props();

	const path = page.url.pathname;

	$effect( () => {
		if( data.breeder && data.district ) {
			console.log( data.breeder, data.district );
			app.title = `Zuchter ${data.breeder.firstname} ${data.breeder.infix} ${data.breeder.lastname} im ${data.district.name}`;
			app.menu.trail = [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: data.district.short, href: '/moderator/' + data.district.id},
				{name: 'Zuechter', href: '/moderator/' + data.district.id + '/breeder'},
				{
					name: `${data.breeder.firstname.charAt(0)}.${data.breeder.lastname.charAt(0)}`,
					href: '/moderator/' + data.district.id + '/breeder/' + data.breeder.id
				},
			];
			app.menu.options = [
				{name: 'Stämme', href: '/moderator/' + data.district.id + '/breeder/' + data.breeder.id + '/pair'},
				{name: 'Mitglied', href: '/moderator/' + data.district.id + '/breeder/' + data.breeder.id + '/profile'},
			];
		}
	} );

	console.log( 'Results page', path );

</script>

{#if data.breeder}
	Breeder {data.breeder.firstname}
{/if}



