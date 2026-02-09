/*
  Mini router for SIR Acatlán
  Author: César Luna
  Version: 1.0.0
 */
import { metadata } from "./services/metadata.js";
const md = metadata.getAll();

console.log("[INFO] Welcome to SIR Acatlán");
console.log(
	"[INFO] Si te solicitan pegar algo en consola, cuidado. Te están timando.",
);

const routes = {
	"/": "/public/components/home.html",
	"/login": "/public/components/login.html",
	"/report_form": "/public/components/report_form.html",
	"/search-report": "/public/components/search_report.html",
};

async function loadRoute(route) {
	const path = routes[route] || routes["404"];

	try {
		const response = await fetch(path);
		if (!response.ok) throw new Error("No se pudo cargar la vista");
		const html = await response.text();

		const app = document.getElementById("app");
		app.innerHTML = html;

		initView(route);
	} catch (err) {
		console.error(err);
		document.getElementById("app").innerHTML =
			`<section><h1>Error</h1><p>No se pudo cargar la vista.</p></section>`;
	}
}

function getCurrentRoute() {
	const hash = window.location.hash || "#/";
	const route = hash.replace("#", "");
	return route;
}

function handleRouteChange() {
	const route = getCurrentRoute();
	loadRoute(route);
}

window.addEventListener("hashchange", handleRouteChange);
window.addEventListener("DOMContentLoaded", handleRouteChange);

function initView(route) {
	if (route === "/login") {
		const form = document.getElementById("login-form");
		if (form) {
			form.addEventListener("submit", (e) => {
				e.preventDefault();
				const data = new FormData(form);
				const email = data.get("email");
				const password = data.get("password");
				console.log("Login con:", email, password);
				// aquí luego conectas con tu API
			});
		}
	} else if (route === "/report_form") {
		// TODO: hacer el ejercicio de insertar las opciones dinamicamente obteniendolas de la variable global de metadata
		const form = document.getElementById("incident-form");
		const incidentList = document.getElementById("incidents");
		const option = document.createElement("option");
		option.new;
		incidentList.appendChild(option);
	}
	// if (route === '/otra-ruta') { ... }
}
