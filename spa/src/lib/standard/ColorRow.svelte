<script>

    export let color = null;

</script>

<div class='flex flex-col pl-8'>
    <div class='flex flex-row border-b border-gray-300 px-4 gap-x-1'>
        <div class='w-12'>{color.id}</div>

        <div class='grow'>
            {color.name}
        </div>
    </div>
</div>
