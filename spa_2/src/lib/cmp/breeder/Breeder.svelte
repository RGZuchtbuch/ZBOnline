<script>
	import { page } from '$app/state';
	let { breeder } = $props();

</script>

<section class='mt-16 px-2 md:px-16 flex flex-col items-center'>
	<h2>Hallo {breeder.firstname}, wilkommen in dein Zuchtbuch</h2>
	<p>Du kannst ier deine Stämme verwalten und deine Mitgliedsdaten einsehen.</p>
	<ul class=''>
		<li><a href={`${page.url.pathname}/pair`}>Stämme</a></li>
		<li><a href={`${page.url.pathname}/profile`}>Mitgliedsdaten</a></li>
	</ul>
</section>

