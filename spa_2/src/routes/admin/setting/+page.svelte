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
			title: 'Admin: System settings',
			menu: {
				trail : [
					{ name:'Start',    href:'/' },
					{ name:'Admin',    href:'/admin' },
					{ name:'Settings' },
				],
				options : [
					{ name:'Verbände', href:'/admin/district' },
					//{ name:'Settings', href:'/admin/setting' },
					{ name:'Logs', href:'/admin/log' },
				],
			}
		}
	}

</script>

System settings, TODO



