<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';
	//import Breeder from '$lib/cmp/breeder/Breeder.svelte';



	const path = page.url.pathname;

	$effect( () => {
		if( app.breeder && app.district ) {
			console.log( app.breeder, app.district );
			app.title = `Zuchter ${app.breeder.firstname} ${app.breeder.infix} ${app.breeder.lastname} im ${app.district.name}`;
			app.menu.trail = [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: app.district.short, href: '/moderator/' + app.district.id},
				{name: 'Zuechter', href: '/moderator/' + app.district.id + '/breeder'},
				{
					name: `${app.breeder.firstname.charAt(0)}.${app.breeder.lastname.charAt(0)}`,
					href: '/moderator/' + app.district.id + '/breeder/' + app.breeder.id
				},
			];
			app.menu.options = [
				{name: 'Stämme', href: '/moderator/' + app.district.id + '/breeder/' + app.breeder.id + '/pair'},
				{name: 'Mitglied', href: '/moderator/' + app.district.id + '/breeder/' + app.breeder.id + '/profile'},
			];
		}
	} );

	console.log( 'Results page', path );

</script>

{#if app.breeder}
	Breeder {app.breeder.firstname}
{/if}



