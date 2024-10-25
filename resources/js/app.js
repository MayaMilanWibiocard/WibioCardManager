
import './bootstrap';
import 'laravel-datatables-vite';
import 'bootstrap';
import Alpine from 'alpinejs';
import '../../node_modules/bootstrap-select/dist/js/bootstrap-select.min.js';

window.Alpine = Alpine;

Alpine.start();

import.meta.glob([
    '../images/**',
    '../fonts/**',
  ]);


    const dropdownSelects = document.getElementsByClassName('dropdown-menu');
    for (let i = 0; i < dropdownSelects.length; i++) {
        dropdownSelects[i].addEventListener('change', async (event) => {
            const dropdownButton = document.getElementById('dropdownBtn_' + dropdownSelects[i].id);
            const hiddenVal = document.getElementById("hidden_" + dropdownSelects[i].id);
            const checkbox = event.target;
            let mySelectedItems = (hiddenVal.innerText == "")? [] : hiddenVal.innerText.split(',');
            if (checkbox.checked) {
                mySelectedItems.push(checkbox.value);
            } else {
                mySelectedItems = mySelectedItems.filter((item) => item !== checkbox.value);
            }
            dropdownButton.innerText = mySelectedItems.length > 0 ? mySelectedItems.length + ' selected' : 'Select Items';
            hiddenVal.value = mySelectedItems;
        });
    }


    const AjaxRequestlist = document.getElementsByClassName("ajax_request");
    for (let i = 0; i < AjaxRequestlist.length; i++) {
        AjaxRequestlist[i].addEventListener("click", async () => {
            const action = AjaxRequestlist[i].getAttribute("data-action");
            const response = await fetch(action, {
                method: "GET",
            });
            console.log(await response.json());
        });
    }
