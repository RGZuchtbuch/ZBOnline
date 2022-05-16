<script>
    import { fade,fly } from 'svelte/transition';
</script>

<div in:fly={{ y:+400 }} out:fade class='flex flex-col m-4'>
    <slot></slot>
</div>