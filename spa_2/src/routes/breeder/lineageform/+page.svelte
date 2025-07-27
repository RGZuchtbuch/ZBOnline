<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import {shortName, fullName, addCrumb} from '$lib/js/tools.js';
	import {onMount} from 'svelte';
	import {page} from '$app/state';

	//let { data } = $props();

	console.log( 'Lineage' );

	// $effect( () => {
	// 	ctx.breeder = data.breeder;
	// });

	$effect( () => {
		if( true ) {
			setHeader();
			addCrumb( { name:'Abstammungsnachweis', url:page.url } );
		}
	});

	function setHeader() {
		ctx.header = {
			title: `Abstammungsnachweis zur Zuchtbuchschau`,
			menu: {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Züchter', href: '/breeder'},
					{name: 'Abstammungsnachweis' },
				],
				options: [
					{name: 'Bewertungsrechner', href: '/breeder/grader'},
//					{name: 'Abstammungsnachweis', href: '/breeder/lineageform'},
				],
			}
		}

	}

</script>

Abstammungsnachweis
