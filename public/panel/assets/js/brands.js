import { sendHttpRequest } from "./sendRequest.js";

const brand = document.getElementById('brand');
const model = document.getElementById('model');
const year = document.getElementById('year');
const trim = document.getElementById('trim');
const model_spinner = KTUtil.getById("model_spinner");
const trim_spinner = KTUtil.getById("trim_spinner");

brand.addEventListener('change', () => {
    KTUtil.btnWait(model_spinner, "spinner spinner-right spinner-success pr-15", "Please wait");
    sendHttpRequest("POST", Routes.select_brand, {
        brand_guid: brand.value,
    }).then((response) => {
        let f = [];
        const res = JSON.parse(response);
        model.disabled = false;
        model.innerHTML = "";
        f.push(
            `<option value="">Please Select Model</option>`
        );
        Array.from(res).forEach((e) => {
             f.push(
                `<option value="${e.model_guid}">${e.name_en}</option>`
            );
            model.innerHTML = f;
        });
        KTUtil.btnRelease(model_spinner);
    });
})

model.addEventListener('change', () => {
    year.disabled = false;
});

year.addEventListener('change', () => {
    if (model.value === "") {
        return;
    }
    KTUtil.btnWait(trim_spinner, "spinner spinner-right spinner-success pr-15", "Please wait");
    sendHttpRequest("POST", Routes.select_model, {
        model_guid: model.value,
        year: year.value,
    }).then((response) => {
        let a = [];
        const res = JSON.parse(response);
        if (res.length > 0) {
            trim.disabled = false;
            trim.innerHTML = "";
            a.push(
                `<option value="">Please Select Trim</option>`
            );
            Array.from(res).forEach((e) => {
                a.push(
                    `<option value="${e.trim_guid}">${e.name_en}</option>`
                );
                trim.innerHTML = a;
            })
        }else {
            a.push(
                `<option value="">no trim for this year</option>`
            );
            trim.innerHTML = a;
            trim.disabled = true;
        }

        KTUtil.btnRelease(trim_spinner);
    });
});
