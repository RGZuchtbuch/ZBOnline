<script>
	import { ctx } from '$lib/js/store.svelte.js';
    import Districts from '$lib/cmp/moderator/Districts.svelte';

	$effect( () => {
		ctx.header = {
			title: 'Admin: Verbände zum Verwalten',
			menu: {
				trail : [
					{ name:'Start',    href:'/' },
					{ name:'Admin',    href:'/admin' },
					{ name:'Verbände', href:'/admin/district' },
				],
				options : [
				],
			}
		}
	})

</script>

Districts to moderate



