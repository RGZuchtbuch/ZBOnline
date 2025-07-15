
export async function load( { depends, params, parent } ) {

	let data = await parent(); // wait for federation to have loaded

	const { federation } = await parent();

	const district = federation.districts[ +params.district ];//store.federation.districts[ +page.params.district ];
	return { district:district };
}
