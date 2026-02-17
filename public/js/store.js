// js/store.js

import { Metadata } from "./services/metadata.js";

export const Store = {
	state: {
		metadata: null,
		isLoaded: false,
	},

	async fetchMetadata() {
		if (this.state.isLoaded === true) return this.state.metadata;
		try {
			const data = await Metadata.getAll();
			this.state.isLoaded = true;
			this.state.metadata = data;
			return data;
		} catch (e) {
			console.error(e);
			throw e;
		}
	},
};
