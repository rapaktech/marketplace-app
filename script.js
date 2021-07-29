// Note this js file was purely used for front-end scripting, to make a textbox appear dynamically

var form = document.getElementById("radio-form");
const rbs = document.querySelectorAll('input[name="radio"]');
const update = document.querySelector('#update-btn');
var x = document.createElement("INPUT");
var y = document.createElement("TEXTAREA");
var w = document.createElement("INPUT");
var z = document.createElement("INPUT");
z.setAttribute("type", "submit");
z.setAttribute("name", "save");
z.setAttribute("value", "Save");
x.setAttribute("type", "text");
x.setAttribute("name", "update-name");
y.setAttribute("type", "text");
y.setAttribute("name", "update-description");
w.setAttribute("type", "number");
w.setAttribute("name", "update-price");


function handleClick(myRadio) {
    x.value = myRadio.id;
    y.value = myRadio.placeholder;
    w.value = myRadio.size;
}

update.addEventListener("click", updateFn);


function updateFn () {
    form.appendChild(x);
    form.appendChild(y);
    form.appendChild(w);
    form.appendChild(z);
}