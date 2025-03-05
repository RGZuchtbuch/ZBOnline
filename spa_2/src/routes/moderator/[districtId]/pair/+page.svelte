<script>
	import {getContext} from 'svelte';

	import District from '$lib/cmp/moderator/District.svelte';
	import Pairs from '$lib/cmp/moderator/district/Pairs.svelte';

	let { data } = $props()
	let state    = getContext( 'state' );

	state.title = `Stämme und Paare für ${ data.district.name }`;
	state.menu = {
		trail : [
			{ name:'Home',      href:'/' },
			{ name:'Obmann',    href:'/moderator' },
			{ name: data.district.short,   href:`/moderator/${data.district.id}` },
			{ name:'Stämme', href:`'/moderator/${data.district.id}/pair` },
		],
		options : [],
	};


</script>

<Pairs {data} />



