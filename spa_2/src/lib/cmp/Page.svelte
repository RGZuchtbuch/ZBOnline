<script>
	import { fade, slide } from 'svelte/transition';
</script>


<section class='h-16 flex flex-row pl-16 border-b border-gray-500 bg-teal-100'>
	<section class='grow pt-2.5'>
		<slot name='trail' />
	</section>

	<section class='flex flex-row pt-2.5' in:slide={{axis:'x'}}>
		<slot name='menu' />
	</section>

	<div class='h-8 flex flex-row pt-1 px-2'>
		<a href='/user' class=''>
			<svg xmlns="http://www.w3.org/2000/svg" height='100%' viewBox="0 0 32 32"><path fill="currentColor" d="M12 4a5 5 0 1 1-5 5a5 5 0 0 1 5-5m0-2a7 7 0 1 0 7 7a7 7 0 0 0-7-7zm10 28h-2v-5a5 5 0 0 0-5-5H9a5 5 0 0 0-5 5v5H2v-5a7 7 0 0 1 7-7h6a7 7 0 0 1 7 7zm0-26h10v2H22zm0 5h10v2H22zm0 5h7v2h-7z"/></svg>
		</a>
	</div>
</section>

<section class='border-0 border-gray-400'>
	<div in:fade>
		<slot name='title' />
	</div>
</section>

<section class='screen-scroll-y border-0' >
	<div class='flex flex-col' in:fade><!-- so only content fades -->
		<slot name='content' />
	</div>
</section>

<footer class='footer'>
	<div class='flex flex-row text-xs' in:fade>
		<slot name='footer' />
	</div>
</footer>



<style>

    @media screen { /* only scroll content on screen, not on print */
        .screen-scroll-y {
            @apply h-full min-h-0 overflow-y-scroll;
        }
    }
    @media print { /* hide footer in print */
        .footer {
            @apply hidden;
        }
    }
</style>