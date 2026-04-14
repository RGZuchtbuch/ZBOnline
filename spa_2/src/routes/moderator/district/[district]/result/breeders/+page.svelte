<script>
	import Breeders from '$lib/cmp/result/Breeders.svelte'
	import model from '$lib/js/model.js';
	import {ctx} from '$lib/js/store.svelte.js';
	import {page} from '$app/state';

	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Im ${ctx.district.name}, Züchter Gesamt Daten eingeben ${ctx.year}`;
		ctx.submenu = [
//			{name: 'Eingaben', href: `/moderator/district/${ctx.district.id}/result?year=${ctx.year}`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: 'Verbände', href: '/moderator/district'},
			{name: 'Verband', href:`/moderator/district/${ctx.district.id}`},
			{name: 'Leistungen', href: `/moderator/district/${ctx.district.id}/result?year=${ctx.year}`},
			{name: 'Züchter'},
		];
	}

</script>



<Breeders />