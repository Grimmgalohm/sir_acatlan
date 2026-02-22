/*
  Mini router for SIR Acatlán
  Author: César Luna
  Version: 1.0.0
 */
import { Store } from "./store.js";

console.info("[INFO] Welcome to SIR Acatlán");
console.info(
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
		const metadata = await Store.fetchMetadata();
		const response = await fetch(path);
		if (!response.ok) throw new Error("No se pudo cargar la vista");
		const html = await response.text();
		const app = document.getElementById("app");
		app.innerHTML = html;
		initView(route, metadata);
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

function initView(route, metadata) {
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
		console.log(metadata.metadata);
		loadMetadata(metadata.metadata);
	}
	// if (route === '/otra-ruta') { ... }
}

function loadMetadata(metadata) {
	const meta_cat_incident = metadata.CategoryIncident;
	const meta_buildings = metadata.Buildings;
	const meta_zone = metadata.Zone;
	const meta_toilets = metadata.Toilet;

	const cat_incident = document.getElementById("incidents");
	const buildings = document.getElementById("buildings");
	const zone = document.getElementById("zone");
	const toilets = document.getElementById("toilets");

	meta_cat_incident.forEach((item) => {
		cat_incident.add(new Option(item.nombre_es, item.id));
	});

	meta_buildings.forEach((item) => {
		const opt = new Option(item.nombre, item.id);
		opt.setAttribute("data-zone-id", item.zona_id);
		buildings.add(opt);
	});

	meta_zone.forEach((item) => {
		const opt = new Option(item.nombre, item.id);
		zone.add(opt);
	});

	meta_toilets.forEach((item) => {
		var type = "";
		switch (item.tipo) {
			case "H":
				type = "Hombres";
				break;
			case "M":
				type = "Mujeres";
				break;
			default:
				type = "Mixto";
		}
		const opt = new Option(type, item.id);
		opt.setAttribute("data-building-id", item.edificio_id);
		toilets.add(opt);
	});

	cat_incident.addEventListener("change", () => {
		const desc = meta_cat_incident.find((el) => el.id == cat_incident.value);
		document.getElementById("incident-desc").textContent = desc.descripcion;
	});

	buildings.addEventListener("change", () => {
		console.log("Algo va aqui...");
	});
	// TODO: continue popping zone elements
	zone.addEventListener("change", () => {
		const desc = meta_zone.find((el) => el.id == zone.value);
	});

	// TODO: condition lists of zones by building selected (needs to match zone_id)
	// condition no gender bathroom by the selected building (actualy just one has that kind)
	// Render all to select > options tags}
	// start catching the form data on send press button}
}
