<script>

    export let breeder;

    let clubs = [];
    let districts = [];

    function onChange( event ) {}

    function onSubmit( event ) {

    }
</script>

{#if breeder.edit && breeder.details }

    <form class='flex flex-col border border-gray-400 rounded m-4 p-2' on:input={onChange}>

        <Text class='w-64' bind:value={breeder.details.name} label='Name' required/>

        <Text class='w-128' bind:value={breeder.details.email} label='Email Adresse'/>

        <Select class='w-64' label='Ortsverein' bind:value={breeder.details.clubId} required>
            {#if clubs}
                {#each clubs as club}
                    <option class='bg-white' value={club.id}> {club.name} </option>
                {/each}
            {/if}
        </Select>
        <div class='flex gap-2'>
            <Date class='w-24' label='Mitglied seit' bind:value={breeder.details.start} required/>
            <Date class='w-24' label='Mitglied bis' bind:value={breeder.details.end} />
        </div>
        <Text class='w-128' label='Info' bind:value={breeder.details.info} />

        {#if breeder.changed}
            <Button class='edit' on:click={onSubmit(breeder)} label='' value='speichern' />
        {/if}
    </form>
{/if}