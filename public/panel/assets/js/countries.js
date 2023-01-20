import { sendHttpRequest } from "./sendRequest.js";

const countries = document.getElementById("countries");
const cities = document.getElementById("cities");
const select_country = document.getElementById("select_country");
const country = document.getElementById("country");
const city = document.getElementById("city");

let f = [];
let removeChange;

const country_load = (e) => {
    if (e !== 1) {
        cities.disabled = false;
        select_country.addEventListener(
            "change",
            (removeChange = (e) => {
                f = [];
                cities.innerHTML = `<option>Please wait a bit.</option>`;
                sendHttpRequest("POST", Routes.select_country, {
                    country_guid: countries.value,
                }).then((response) => {
                    const res = JSON.parse(response);
                    Array.from(res.cities).forEach((city) => {
                        f.push(
                            `<option value="${city.city_guid}">${city.name}</option>`
                        );
                    });
                    cities.innerHTML = f;
                });
            })
        );
    }
};
country.addEventListener("click", () => {
    if (removeChange !== undefined) {
        select_country.removeEventListener("change", removeChange);
    }
    cities.disabled = true;
    cities.innerHTML = `<option value="">if you will add a city click on city</option>`;
    country_load(1);
});

city.addEventListener("click", () => {
    country_load();
});

if (city.checked) {
    country_load();
}
