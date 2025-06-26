<script>
	import { getContext } from 'svelte';
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import Profile from '$lib/cmp/breeder/profile.svelte';
	import {Breeder} from '$lib/js/breeder.svelte.js';
	import {District} from '$lib/js/model/federation.svelte.js';
//	import api from '$lib/js/api.js.obs';
	import { txt } from '$lib/js/toolbox.js';

	let { data } = $props();
	let breeder  = $state( data.breeder );
	let district = $state( data.district );

	$effect( async () => {
		breeder = await Breeder.load( +page.params.breeder, +page.params.district );
		district = await District.load( +page.params.district );
		setHeader( breeder );
	})

	function setHeader( breeder ) {
		const title = `Mitgliedsdaten für Züchter ${txt(breeder.firstname)} ${txt(breeder.infix)} ${txt(breeder.lastname)}`;
		const menu = {
			trail: [
				{ name:'Home',              href:'/' },
				{ name:'Obmann',            href:'/moderator' },
				{ name:district.short, href:`/moderator/${district.id}` },
				{ name:'Züchter',      href:`/moderator/${district.id}/breeder` },
				{
					name:`${breeder.firstname.charAt(0)}.${breeder.lastname.charAt(0)}`,
					href:`/moderator/${district.id}/breeder/${breeder.id}`,
				},
				{ name:'Mitglied',     href:page.url.href },
			],
			options: [
				{name: 'Stämme', href: '/moderator/' + district.id + '/breeder/' + breeder.id + '/pair'},
			],
		}
		store.title = title; // to set after loading
		store.menu = menu;
	}



</script>

<Profile {breeder} {district} />
