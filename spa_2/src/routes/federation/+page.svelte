<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';

	import Districts from '$lib/cmp/district/Districts.svelte';

	let { data } = $props();

	$effect( () => {
		ctx.header = {
			title : 'Landesverbände im BDRG Zuchtbuch',
			menu : {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Verbände', href: '/district'},
				],
				options: [
					{name: 'Beiträge', href: '/article'},
					{name: 'Standard', href: '/standard'},
					{name: 'Leistungen', href: '/report'},
				],
			},
		}
	});
	//let state = getContext( 'state' )
	function setHeader( data ) {
		ctx.header.title = 'Landesverbände im BDRG Zuchtbuch'
		ctx.header.menu = {
			trail: [
				{name: 'Start', href: '/'},
				{name: 'Verbände', href: '/district'},
			],
			options: [
				{name: 'Beiträge', href: '/article'},
				{name: 'Standard', href: '/standard'},
				{name: 'Leistungen', href: '/report'},
			],
		}
	}

</script>

<Districts root={ctx.federation}/>


