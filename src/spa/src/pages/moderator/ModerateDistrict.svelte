<script>
    import Box from '../../components/Box.svelte';
    export let districtId;

</script>

<div class='flex flex-row justify-between w-128'>
    <Box legend='Obmann menu'>
        <div>Mitglieder</div>
        <div>Leistungen</div>
        <div>Berichte Drucken</div>
    </Box>
    <Box legend={'Obmann für'}>
        <div class='flex flex-col'>
            Obmann Bezirk {districtId}

            <div><a href={'/#/obmann/verband/'+districtId+'/zuechter'}>Mitglieder verwalten</a></div>

            <div><a href={'/#/obmann/verband/'+districtId+'/leistungen'}>Leistungen verwalten</a></div>
            <div><a href={'/#/obmann/verband/'+districtId+'/berichte'}>Berichte Drucken</a></div>
        </div>
    </Box>
     <div>-</div>
</div>