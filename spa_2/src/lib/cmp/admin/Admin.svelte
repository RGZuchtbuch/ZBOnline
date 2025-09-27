<script>
	import { page } from '$app/state';
	import { cfg, ctx } from '$lib/js/store.svelte.js';
	//let { breeder } = $props();

</script>

<section class='mt-32 flex flex-col items-center'>
	<h2>Hallo { ctx.user.firstname}, Willkommen in das Zuchtbuch</h2>
	<p>Als Admin kannst du Info's, Verbände, den Standard usw Verwalten.</p>

	<ul class='mr-16'>
		<li><a href={`/admin/article`}>Infos</a></li>
		<li><a href={`/admin/district`}>Verbände</a></li>
		<li><a href={`/admin/standard`}>Standard</a></li>
		<li><a href={`/admin/setting`}>Settings</a></li>
		<li><a href={`/admin/log`}>Logs</a></li>
	</ul>
</section>


