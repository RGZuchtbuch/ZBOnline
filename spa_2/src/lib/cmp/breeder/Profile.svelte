<script>


    let { breeder, district } = $props();
</script>

{#if breeder && district }
    <div>
        Form for showing and editting breeder {breeder.firstname} {breeder.email} in { district.short}
    </div>
{/if}