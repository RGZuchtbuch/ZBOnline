<script>
</script>


<h3 class='title'> <slot name='title'> <span>Title ?</span> </slot> </h3>
<div class='header'> <slot name='header'> <span>Header ?</span> </slot> </div>
<div class='flex flex-col body scrollbar'> <slot name='body'> <span>Body ?</span> </slot></div>

<style>
    article {
        @apply w-256;
    }
    .title {
        @apply w-256 text-center;
    }
    .header {
        @apply w-256 border rounded-t border-gray-400 px-2 py-2 bg-header font-bold text-white;
    }
    .body {
        @apply w-256 border rounded-b border-gray-400 bg-gray-100 px-2 text-black overflow-y-scroll;
    }
</style>

