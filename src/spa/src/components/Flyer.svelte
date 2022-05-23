<script>
    import { fade,fly } from 'svelte/transition';
</script>

<div in:fade out:fade class='flex flex-col m-4 border'>
    <slot></slot>
</div>

<style>
    div {
        @apply border-red-600;
    }
</style>