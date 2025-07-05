<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import Profile from '$lib/cmp/breeder/profile.svelte';
	import { txt } from '$lib/js/tools.js';

	let { data } = $props();

	console.log( 'BP', data );

	$effect( async () => {
		setHeader( data.breeder, data.district );
	})

	function setHeader( breeder, district ) {
		ctx.header.title = `Mitgliedsdaten für Züchter ${txt(breeder.firstname)} ${txt(breeder.infix)} ${txt(breeder.lastname)}`;
		ctx.header.menu = {
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
	}



</script>

<Profile breeder={data.breeder} district={data.district} />
