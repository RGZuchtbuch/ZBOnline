<script>
	let { breeder, district, pairs } = $props();
</script>

<h3>{breeder.firstname}</h3>

Chck
{#each pairs as pair}
	<div>
		<a href={`/moderator/${pair.districtId}/breeder/${pair.breederId}/pair/${pair.id}`}>
			{pair.name} {pair.breedId} {pair.breedName} {pair.firstname} {pair.infix} {pair.lastname}
		</a>
	</div>
{/each}
