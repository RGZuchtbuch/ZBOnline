<script>
	import { getContext } from 'svelte';
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import BreederProfile from '$lib/cmp/breeder/profile.svelte';

	let { data } = $props();
	let state = getContext( 'state' );

	state.title = `Mitgliedsdaten für Züchter ${data.breeder.firstname} ${data.breeder.infix} ${data.breeder.lastname} im ${data.district.name}`;
	state.menu = {
		trail : [
			{ name:'Home',              href:'/' },
			{ name:'Obmann',            href:'/moderator' },
			{ name:data.district.short, href:'/moderator/'+data.district.id },
			{ name:'Züchter',          href:'/moderator/'+data.district.id+'/breeder' },
			{ name:`${data.breeder.firstname.charAt(0)}.${data.breeder.lastname.charAt(0)}`, href:'/moderator/'+data.district.id+'/breeder/'+data.breeder.id },
			{ name:'Mitglied',          href:data.url.pathname },
		],
		options : [],
	};
</script>

<BreederProfile {data}/>
