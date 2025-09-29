import api from '$lib/js/server.js';

export default class Cache {

	static async delete() {
		return await api.delete(`/api/2/cache` );
	}
}