// Note this js file was purely used for front-end scripting, to make a textbox appear dynamically

const update = document.querySelector('#update-btn');

update.onclick = function () {
    if (document.querySelector('input[name="update"]')) {
        return;
    } else {
        const rbs = document.querySelectorAll('input[name="radio"]');
        for (const rb of rbs) {
            if (rb.checked) {
                var x = document.createElement("INPUT");
                var y = document.createElement("TEXTAREA");
                var w = document.createElement("INPUT");
                x.setAttribute("type", "text");
                x.setAttribute("name", "update-name");
                y.setAttribute("type", "text");
                y.setAttribute("name", "update-description");
                w.setAttribute("type", "number");
                w.setAttribute("name", "update-price");
                x.value = rb.id;
                y.value = rb.placeholder;
                w.value = rb.size;
                var z = document.createElement("INPUT");
                z.setAttribute("type", "submit");
                z.setAttribute("name", "save");
                z.setAttribute("value", "Save");
                let form = document.getElementById("radio-form");
                form.appendChild(x);
                form.appendChild(y);
                form.appendChild(w);
                form.appendChild(z);
                break;
            } else {
                continue;
            }
        }
    }
}