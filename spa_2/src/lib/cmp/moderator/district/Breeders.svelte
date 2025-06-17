<script>
	import { page } from '$app/state';

	let {breeders, district} = $props();

</script>


<div class='flex flex-row justify-end gap-x-4'>
	<a href={`${page.url.href}/0`}>[+]</a>
</div>

<header class='flex flex-row header section items-end pl-6'>
	<span class='w-12'>ZbNr</span>
	<span class='w-48'>Name</span>
	<span class='w-48'>Ortsverein</span>
	<span class='w-24'>Seit</span>
	<span class='w-24'>Bis</span>
</header>

{#each breeders as breeder }
	<a class='flex' href={page.url.pathname+'/'+breeder.id}>
		<span class='w-12'> {breeder.member}</span>
		<span class='w-48'> {breeder.lastname}, {breeder.firstname} {breeder.infix}</span>
		<span class='w-48'> {breeder.club}</span>
		<span class='w-24'> {breeder.start}</span>
		<span class='w-24'> {breeder.end}</span>
	</a>
{/each}



<style>
    h3 {
        @apply text-center text-xl bg-teal-200 font-bold sticky top-0;
    }
    hesader {
        @apply flex flex-row border-b bg-slate-100 p-2 gap-x-2 sticky top-0
    }
    p {
        @apply text-center italic;
    }
    a {
        @apply border-b flex flex-row p-2 pl-6 gap-x-2;
    }
</style>