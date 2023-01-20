import { sendHttpRequest } from "./sendRequest.js";

const category= document.getElementById('category_guid');
const subcategory = document.getElementById('subcategory_guid');

category.addEventListener('change', () => {
    sendHttpRequest("POST", Routes.select_category, {
        category_guid: category.value,
    }).then((response) => {
        let f = [];
        const res = JSON.parse(response);
        subcategory.disabled = false;
        subcategory.innerHTML = "";
        if (res.length === 0) {
            f.push(
                `<option value="">No Subcategory</option>`
            );
            subcategory.innerHTML = f;

        }else {
            Array.from(res).forEach((e) => {
                f.push(
                    `<option value="${e.category_guid}">${e.name_en}</option>`
                );
                subcategory.innerHTML = f;
            });
        }
    });
})
