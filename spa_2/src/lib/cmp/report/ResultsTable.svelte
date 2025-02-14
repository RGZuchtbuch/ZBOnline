<script>

</script>


<h1>BDRG Zuchtbuch Leistingsdaten</h1>

{#each ['Groß u. Wassergeflügel', 'Hühner', 'Zwerghühner', 'Legewachteln', 'Tauben'] as section}
	<table class='w-full border border-gray-400 rounded print:border-0 print:break-after-page'>

		<thead class=''>
			<tr class='section'><th colspan=12>{section}</th></tr>
			<tr class='header'>
				<th>Gruppe, Rasse u. Farbenschlag</th>
				<th>Zuchten</th><td class='space'></td><th colspan='2'>Legeleistung</th><td class='space'></td><th colspan='3'>Brutleistung</th><td class='space'></td><th colspan='2'>Schauleistung</th>
			</tr>
		</thead>
		{#each ['Puten', 'Ganse', 'Enten'] as subsection}
			<tbody>
				<tr class='subsection'><th>{subsection}</th><th claspan=11></th></tr>
				<tr class='subheader'><th >Rasse und Farbenschlag</th><th>Zuchten</th><td class='space'></td><th>Eier/J</th><th>Gew.</th><td class='space'></td><th>Eier</th><th>Befr.</th><th>Küken</th><td class='space'></td><th>Tiere</th><th>Punkte</th></tr>
				{#each ['Bielefelder Zwerg kennhuhn', 'Welsumer', 'Sussex'] as breed }
					<tr class='breed'><td>{breed}</td><td>6</td><td class='space'></td><td>79%</td><td>50</td><td class='space'></td><td>241</td><td>85%</td><td>66%</td><td class='space'></td><td>33</td><td>93.4</td></tr>
					{#each ['kennsperber', 'silber-kennsperber'] as color }
						<tr class='color'><td>{color}</td><td>6</td><td class='space'></td><td>79%</td><td>50</td><td class='space'></td><td>241</td><td>85%</td><td>66%</td><td class='space'></td><td>33</td><td>93.4</td></tr>
					{/each}
				{/each}
				<tr class='subsection-total'><td>Gesamt {subsection}</td><td>6</td><td class='space'></td><td>79%</td><td>50</td><td class='space'></td><td>241</td><td>85%</td><td>66%</td><td class='space'></td><td>33</td><td>93.4</td></tr>
			</tbody>
		{/each}
		<tr class='section-total'><td>Gesamt {section}</td><td>6</td><td class='space'></td><td>79%</td><td>50</td><td class='space'></td><td>241</td><td>85%</td><td>66%</td><td class='space'></td><td>33</td><td>93.4</td></tr>
	</table>
{/each}
<div>Overall total TODO</div>


<style>

	table th {
		@apply w-12;
	}

    .section th {
        @apply sticky top-0 bg-white/75 backdrop-blur-sm border-b border-gray-400 text-center text-2xl box-border;
    }
    .header {
        @apply bg-slate-200 border-y border-slate-400 text-center font-bold;
    }
    .header th:first-child {
	    @apply w-1/2;
    }
    .subsection {
        @apply pt-2 text-center text-xl;
    }
    .subheader {
         @apply bg-slate-200 border-y border-slate-400 text-center text-sm font-normal;
    }

	td:first-child, th:first-child {
		@apply px-2;
	}
    td:last-child, th:last-child {
	    @apply pr-1;
    }
	.breed {
		@apply bg-slate-100 font-bold text-right;
	}
	.breed td:first-child {
		@apply text-left;
	}
	.color {
		@apply text-right;
	}
    .color td:first-child {
        @apply pl-4 text-left;
    }
	.subsection-total {
		@apply border-t border-slate-400 italic text-right;
	}
	.subsection-total td:first-child {
        @apply text-center;
	}
	.section-total {
		@apply bg-slate-200 border border-slate-400 font-bold text-right;
	}
	.section-total td:first-child {
        @apply text-center;
	}
	.space {
		@apply w-4;
	}

</style>