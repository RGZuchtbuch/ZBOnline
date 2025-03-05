<script>
	import {getContext} from 'svelte';
	import District from '$lib/cmp/moderator/District.svelte';


    let { data } = $props();
	let state     = getContext( 'state' );
	let district = getContext( 'district' );

	let year = new Date().getFullYear();

	state.title = `Verbande ${district.name} verwalten`;
	state.menu = {
		trail: [
			{name: 'Home', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: district.short, href: `/moderator/${district.id}`},
		],
		options: [
			{name: 'Leistungen', href: `/moderator/${district.id}/result`},
			{name: 'Stämme', href: `/moderator/${district.id}/pair`},
			{name: 'Züchter', href: `/moderator/${district.id}/breeder`},
			{name: 'Berichte', href: `/moderator/${district.id}/report/${year}`},
		]
	};



</script>

<District {...data} />

