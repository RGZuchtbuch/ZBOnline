<script>
	let { district, pairs } = $props();
</script>

<h3>{district.name}</h3>

{#each pairs as pair}
	<div>
		<a href={`/moderator/${pair.districtId}/breeder/${pair.breederId}/pair/${pair.id}`}>
			{pair.name} {pair.breedId} {pair.breedName} {pair.firstname} {pair.infix} {pair.lastname}
		</a>
	</div>
{/each}
