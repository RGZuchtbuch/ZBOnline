<script>
	import { page } from '$app/state';
	import { store } from '$lib/js/store.svelte.js';
	import BreederProfile from '$lib/cmp/breeder/profile.svelte';

	let { data } = $props();

	app.title = `Mitgliedsdaten für Züchter ${data.breeder.firstname} ${data.breeder.infix} ${data.breeder.lastname} im ${data.district.name}`;
	app.menu.trail = [
		{ name:'Home',              href:'/' },
		{ name:'Obmann',            href:'/moderator' },
		{ name:data.district.short, href:'/moderator/'+data.district.id },
		{ name:'Zuechter',          href:'/moderator/'+data.district.id+'/breeder' },
		{ name:`${data.breeder.firstname.charAt(0)}.${data.breeder.lastname.charAt(0)}`, href:'/moderator/'+data.district.id+'/breeder/'+data.breeder.id },
		{ name:'Mitglied',          href:page.url.pathname },
	];
	app.menu.options = []
</script>

<BreederProfile {data}/>
