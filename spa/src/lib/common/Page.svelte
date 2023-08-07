<script></script>


<h3 class='title'> <slot name='title'> <span>Title ?</span> </slot> </h3>
<div class='header'> <slot name='header'> <span>Header ?</span> </slot> </div>
<div class='body scrollbar'> <slot name='body'> <span>Body ?</span> </slot></div>

<style>
    article {
        @apply w-256;
    }
    .title {
        @apply w-256 text-center;
    }
    .header {
        @apply w-256 grow border rounded-t border-gray-400 px-4 py-2 bg-header font-bold text-white text-center;
    }
    .body {
        @apply w-256 flex flex-col border rounded-b border-gray-400 bg-gray-100 p-4 text-black overflow-y-scroll;
    }
</style>

