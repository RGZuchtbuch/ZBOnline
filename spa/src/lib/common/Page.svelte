<script>
	import { slide } from 'svelte/transition';
</script>

<div class='h-full w-full flex flex-col min-h-0'>
	<h3 class='title'> <slot name='title'></slot> </h3>
	<div class='header'> <slot name='header'></slot> </div>
	<div class='body overflow-y-scroll scrollbar print-no-scrollbar'> <slot name='body'></slot></div> <!--  in:slide -->
</div>

<style>
    .title {
        @apply border rounded-t border-gray-400 px-4 py-2 bg-header text-xl font-bold text-white text-center italic;
    }
    .header {
	    @apply border-x border-gray-400 bg-gray-100
    }
    .body {
        @apply w-full flex flex-col border rounded-b border-gray-400 bg-gray-50 p-0 text-black; /* scrollbar part in div class for priority */
    }
</style>

