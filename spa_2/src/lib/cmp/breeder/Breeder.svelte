<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js'
	let { breeder } = $props();


	console.log( 'BP', ctx.user.admin )
</script>

<section class='mt-32 flex flex-col items-center'>
	<h2>Hallo {breeder.firstname}, wilkommen in dein Zuchtbuch</h2>
	<p>Du kannst ier deine <a href=''>Stämme</a> verwalten und deine <a href=''>Mitgliedsdaten</a> einsehen.</p>
	<ul class=''>
		<li><a href={`${page.url.pathname}/pair`}>Stämme</a>
		<li><a href={`${page.url.pathname}/profile`}>Mitgliedsdaten</a>
	</ul>
</section>

