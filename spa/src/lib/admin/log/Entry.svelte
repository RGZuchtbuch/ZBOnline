<script>


    export let entry = null;



</script>


<div class='flex flex-row text-sm'>
    <div class='w-12'>{entry.id}</div>
    <div class='w-36'>{entry.modified}</div>
    <div class='w-16'>{entry.method}</div>
    <div class='w-72'>{entry.uri}</div>
    <div class='w-48'>{entry.query}</div>
    <div class='w-48'>{entry.body}</div>
    <div class='w-12'>{entry.modifierId}</div>
</div>