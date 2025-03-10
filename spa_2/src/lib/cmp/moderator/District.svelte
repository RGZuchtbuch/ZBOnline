<script>
	import { page } from '$app/state';
	import api from '$lib/js/api.js';
	import store, { district } from '$lib/js/store.svelte.js';





	load( page.params.districtId )

	export async function load( districtId ) {
		const response = await api.district.get( districtId );
		store.district.update( () => response.district );

		let year = new Date().getFullYear();

		const title = `Verbande ${$district.name} verwalten`;
		const menu = {
			trail: [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: $district.short, href: `/moderator/${$district.id}`},
			],
			options: [
				{name: 'Leistungen', href: `/moderator/${$district.id}/result`},
				{name: 'Stämme', href: `/moderator/${$district.id}/pair`},
				{name: 'Züchter', href: `/moderator/${$district.id}/breeder`},
				{name: 'Berichte', href: `/moderator/${$district.id}/report/${year}`},
			]
		};
		store.title.update( () => title );
		store.menu.update( () => menu );
	}
</script>

{#if district }
	<p>
		Help, statistics, leistungen
	</p>

{/if}