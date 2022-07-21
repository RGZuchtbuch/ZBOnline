<script>
    import { onMount } from 'svelte';

    export let sectionId
    export let breedId;
    export let colorId;

    let sections;
    let breeds;
    let colors;

    onMount( () => {

    });

</script>

<div class='flex flex-col my-2'>
    <div>Rasse</div>
    <div class='flex flex-row gap-x-1'>
        <InputSelect class='w-48' label='Sparte' options={sections} bind:value={report.sectionId} {disabled} required>
            {#each sections as section }
                <option value={section.id}>{section.name}</option>
            {/each}
        </InputSelect>
        <InputSelect class='w-48' label='Rasse' options={breeds} bind:value={report.breedId} {disabled} required>
            {#each breeds as breed }
                <option value={breed.id}>{breed.name}</option>
            {/each}

        </InputSelect>
        <InputSelect class='w-48' label='Farbe' options={colors} bind:value={report.colorId} {disabled} required >
            {#each colors as color }
                <option value={color.id}>{color.name}</option>
            {/each}
        </InputSelect>
    </div>
</div>

<style>

</style>