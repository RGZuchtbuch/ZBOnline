<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js'
	import model from '$lib/js/model.js';
	import { addCrumb } from '$lib/js/tools.js';

	import Article from '$lib/cmp/article/Article.svelte';

	$effect( async () => {
		if( true ) await loadArticle( 1 );
	})
	$effect( () => {
		if( page.url ) setHeader();
	})

	async function loadArticle( id ) {
		dirty.article = false;
		ctx.article = null;
		ctx.article = await model.Article.load( id );
	}

	function setHeader() {
		ctx.title = 'Das BDRG Zuchtbuch';
		ctx.submenu = [];
		ctx.crumbs = [
			//{name: 'Gast', href: '/'}
		];
	}



</script>

<section class='pl-6' in:fade={{duration:cfg.fadeIn}}>
	<h2 class='text-center'>Wilkommen im BDRG Zuchtbuch Online</h2>
	<p>
		Mit dem Rassegeflügelzuchtbuch wollen wir helfen die Leistungen unsere Tiere zu erhalten.  Neben die Schauleistungen vom Preisrichter in den Ausstellungen, sind auch die Legeleistung, Zahl der Eier und deren Gewicht, und Brutleistung, die Befruchtung und Schlüpf, wichtig zum Erhalt und Verbessrung unsere Rassen
	</p>
	<h3>Messen ist Wissen</h3>
	<p class='strong'>Dazu sammelt das BDRG Zuchtbuch die Zucht- und Leistungsdaten der Zuchtbuchmitglieder der Landesverbände und stellt die den Züchtern wieder zu verfügung damit sie ein vergleich haben zu ihrer Zucht und der BDRG die Entwicklung der Rassen, wenn nötig steuern kann.
	</p>
	<p>
		Zudem haben wir auch weitere info's zum Mitmachen im Zuchtbuch, das Ausstellen in den Zuchtbuchschauen und andere Themen zur Zucht von Rassegeflügel.
	</p>

	<h3>Landesverbände und Obleute</h3>
	<p>
		Zusätslich gibt er die <a href='/federation'>&#x1F517; Kontaktdaten der Obmänner der Verbände</a> und ein Verzeichnis der vom <a href='/standard'>&#x1F517; BDRG anerkannten Rassen und Farbenschläge</a>.
	</p>

	<img src='/assets/ZB_ALBS.png' width={256} alt='Abstammung das Fundament der Leistungen'  style='margin:auto' />


	<h2 class='text-center'>Neues vom Zuchtbuch</h2>
	<Article article={ctx.article}/><!-- neues -->
</section>
