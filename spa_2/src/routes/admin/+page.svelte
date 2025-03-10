<script>
	import { getContext } from 'svelte';
    import Districts from '$lib/cmp/admin/Districts.svelte';

	let state     = getContext( 'state' );

	state.title = 'Obmann';
	state.menu = {
		trail : [
			{ name:'Start',    href:'/' },
			{ name:'Admin',    href:'/admin' },
			{ name:'Verbände', href:'/admin/district' },
		],
	    options : [
	    ],
	};



</script>

<Districts districts={districts}/>



