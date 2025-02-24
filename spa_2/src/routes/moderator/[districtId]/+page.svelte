<script>
	import District from '$lib/cmp/moderator/District.svelte';
	import {getContext} from 'svelte';

    let { data } = $props();
	let page     = getContext( 'page' );
	let district = getContext( 'district' );

	page.title = `Verbande ${district.name} verwalten`;
	page.menu = {
		trail: [
			{name: 'Home', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: district.short, href: `/moderator/${district.id}`},
		],
		options: [
			{name: 'Leistungen', href: `/moderator/${district.id}result`},
			{name: 'Stämme', href: `/moderator/${district.id}/pair`},
			{name: 'Züchter', href: `/moderator/${district.id}/breeder`},
			{name: 'Berichte', href: `/moderator/${district.id}/report`},
		]
	};



</script>

<District district={district} />

