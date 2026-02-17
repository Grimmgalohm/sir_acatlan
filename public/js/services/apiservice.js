// public/js/services/apiservices.js

export class ApiService {
	constructor(baseURL = "") {
		this.baseURL = baseURL;
	}

	//Método privado para manejar la logica común del fetch
	async #request(endpoint, options = {}) {
		const url = `${this.baseURL}${endpoint}`;

		// Configuración por defecto.
		const defaultOptions = {
			headers: {
				"Content-Type": "application/json",
			},
			...options,
		};

		try {
			const response = await fetch(url, defaultOptions);

			if (!response.ok) {
				const errorData = await response.json().catch(() => ({}));

				throw {
					status: response.status,
					message: errorData.message || "Error en la petición",
					data: errorData,
				};
			}

			if (response.status === 204) return null;
			return await response.json();
		} catch (error) {
			console.error(`[Api Error] ${error.status}: ${error.message}`);
			throw error;
		}
	}

	get(endpoint) {
		return this.#request(endpoint, { method: "GET" });
	}

	post(endpoint, body) {
		return this.#request(endpoint, {
			method: "POST",
			body: JSONO.stringify(body),
		});
	}

	put(endpoint) {
		return this.#request(endpoint, {
			method: "PUT",
			body: JSON.stringify(body),
		});
	}

	delete(endpoint) {
		return this.#request(endpoint, {
			method: "DELETE",
			body: JSON.stringify(body),
		});
	}
}

//ENV API ENDPOINT LOCALHOST
export const api = new ApiService(
	"http://localhost:8000/API/api_v1.0.0/public/index.php/api",
);
