<script>
	import { getContext } from 'svelte';
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import Profile from '$lib/cmp/breeder/profile.svelte';
	import api from '$lib/js/api.js';

	let { data } = $props();
	let breeder  = $state( data.breeder );
	let district = $state( data.district );

	$effect( () => {
		setHeader( breeder );
	})


	function setHeader( breeder ) {
		const title = null;//`Mitgliedsdaten für Züchter ${breeder.firstname} ${breeder.infix} ${breeder.lastname} im ${district.name}`;
		const menu = {
			trail: [
				{ name:'Home',              href:'/' },
				{ name:'Obmann',            href:'/moderator' },
				{ name:district.short, href:`/moderator/${district.id}` },
				{ name:'Züchter',           href:`/moderator/${district.id}/breeder` },
				{
					name:`${breeder.firstname.charAt(0)}.${breeder.lastname.charAt(0)}`,
					href:`/moderator/${district.id}/breeder/${breeder.id}`,
				},
				{ name:'Mitglied',          href:page.url.href },
			],
			options: [
			],
		}
		store.title.update(() => title); // to set after loading
		store.menu.update(() => menu);
	}

</script>

<Profile {breeder} {district} />






