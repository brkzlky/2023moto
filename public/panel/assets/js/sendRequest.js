export function sendHttpRequest(method, url, data = {}) {
    const promise = new Promise((resolve, reject) => {
        const csrf = document.getElementById("csrfToken").content;
        const formData = new FormData();
        for (const [key, value] of Object.entries(data)) {
            const data = value;
            const name = key;
            formData.append(name, data);
        }
        const xhr = new XMLHttpRequest();
        xhr.open(method, url);
        xhr.setRequestHeader("X-CSRF-TOKEN", csrf);
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 400) {
                resolve(xhr.response);
            } else {
                reject(new Error(`Something went wrong Error: ${xhr.status}`));
            }
        };
        xhr.onerror = function () {
            reject(new Error("Something went wrong!"));
        };
        xhr.send(formData);
    });
    return promise;
}
