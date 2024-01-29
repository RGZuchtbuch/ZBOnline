<script>
    export let label=null;

    let classname = '';
    export { classname as class }

//background: rgba( 255, 255, 255, 0.75 );
</script>

<div class='modal'>
    {#if label} <h2>{label}</h2> {/if}
    <slot></slot>
</div>

<style>
    .modal {
        z-index: 2;
        @apply fixed left-0 top-0 w-full h-full backdrop-blur flex justify-center;
    }

</style>