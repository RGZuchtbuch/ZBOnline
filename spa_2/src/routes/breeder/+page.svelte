<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import Article from '$lib/cmp/article/Article.svelte';
	import {addCrumb} from '$lib/js/tools.js';

 	$effect( async () => {
		setHeader();
		addCrumb( { name:'Züchter', url:page.url } );
 	});

	function setHeader() {
		const header = { // first in local to avoid retriggering when assigning
			title : 'Züchter im Zuchtbuch',
			menu : {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Züchter'},
				],
				options: [
					{name: 'Bewertungsrechner', href: '/breeder/grader'},
					{name: 'Abstammungsnachweis', href: '/breeder/lineageform'},
				],
			},
		};
		if( ctx.user ) {
			header.menu.options.push( {name: 'Mein Zuchtbuch', href: `/breeder/me/${ctx.user.id}`} );
		}
		ctx.header = header; // triggers
	}


</script>

<h2>Breeder page</h2>
<ul class='list-disc pl-2'>

	<li>Info's zum Züchter</li>
	<li>Bewertungsrechner</li>
	<li>Abstammungsnachweis</li>
</ul>



<style>
	ul {
		@apply list-inside text-red-600;
	}
</style>