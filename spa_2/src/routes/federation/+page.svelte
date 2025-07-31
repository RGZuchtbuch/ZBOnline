<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { addCrumb } from '$lib/js/tools.js';

	import Districts from '$lib/cmp/district/Districts.svelte';

	$effect( () => {
		if( page.url ) setHeader();
	});

	function setHeader() {
		addCrumb( { name:'Verbände', url:page.url } );
		ctx.header = {
			title : 'Landesverbände im BDRG Zuchtbuch',
			menu : {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Verbände'},
				],
				options: [
					{name: 'Beiträge', href: '/article'},
					{name: 'Verbände', href: '/federation'},
					{name: 'Standard', href: '/standard'},
					{name: 'Leistungen', href: '/report'},
				],
			},
		}
	}

</script>

{#if ctx.federation}
	<Districts root={ctx.federation}/>
{/if}

