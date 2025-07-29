<script>
	import { ctx } from '$lib/js/store.svelte.js';
    import Districts from '$lib/cmp/moderator/Districts.svelte';

	$effect( async () => {
		if( true ) await load();
	})
	$effect( () => {
		if( true ) setHeader();
	})

	async function load() {

	}

	function setHeader() {
		ctx.header = {
			title: 'Admin: Verbände zum Verwalten',
			menu: {
				trail : [
					{ name:'Start',    href:'/' },
					{ name:'Admin',    href:'/admin' },
					{ name:'Verbände', href:'/admin/district' },
				],
				options : [
					{ name:'Verbände', href:'/admin/district' },
					{ name:'Settings', href:'/admin/setting' },
					//{ name:'Logs', href:'/admin/log' },
				],
			}
		}
	}

</script>

Logs im Bild, TODO



