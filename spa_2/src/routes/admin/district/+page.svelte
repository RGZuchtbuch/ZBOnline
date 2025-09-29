<script>
	import { ctx } from '$lib/js/store.svelte.js';
    import Districts from '$lib/cmp/admin/district/Districts.svelte';
	import {page} from '$app/state';

	$effect( async () => {
		if( true ) await load();
	})
	$effect( () => {
		if( true ) setHeader();
	})

	async function load() {

	}

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;

		ctx.title = 'Admin: Verbände zum Verwalten';
		ctx.submenu = [
		];
		ctx.crumbs = [
			{ name:'Admin',    href:'/admin' },
			{ name:'Verbände', href:'/admin/district' },
		];
	}

</script>

{#if ctx.federation}
	<Districts bind:federation={ctx.federation}/>
{/if}


