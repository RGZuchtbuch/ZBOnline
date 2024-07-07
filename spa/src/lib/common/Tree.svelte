<script>
	import { slide } from 'svelte/transition';
	import Toggler from './OpenClose.svelte';

	export let node; // tree
	export let open = false;
</script>

{#if node}
	<div class='flex flex-col pl-2 md:pl-8'>
		<div class='flex flex-row border-b border-gray-300 my-2'>
			<Toggler bind:open={open} enabled={node.children ? node.children.length > 0 : false} class='text-orange-600'/>
			<div class='grow'> <slot node={node}/> </div>
		</div>

		{#if node && node.children && ( open )}
			<div transition:slide>
				{#each node.children as childNode}
					<svelte:self node={childNode} open={true} let:node={childNode}>
						<slot node={childNode}>No Slot</slot>
					</svelte:self>
				{/each}
			</div>
		{/if}
	</div>
{/if}

<style>
</style>