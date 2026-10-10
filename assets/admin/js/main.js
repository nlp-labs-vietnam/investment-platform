"use strict";
window.addEventListener("DOMContentLoaded", (event) => {
    const el = document.getElementById('filter_close_btn');
    if (el) {
        el.addEventListener('click', function (){
            let dropdownMenu = document.querySelector(".dropdown-menu.show");
            if (dropdownMenu) {
                dropdownMenu.classList.remove("show");
            }
        });
    }
});

window.addEventListener("DOMContentLoaded", (event) => {
    const el = document.getElementById('clear_filter');
    if (el) {
        el.addEventListener('click', function (){
            const filterDateRange = document.getElementById("filter_form");
            filterDateRange.reset();
        });
    }
});

window.addEventListener("DOMContentLoaded", (event) => {
    const el = document.getElementById('flatpickr_filter_date_range');
    if (el) {
        el.addEventListener('click', function (){
            const filterDateRange = document.getElementById("filter_date_range");
            if (filterDateRange) {
                filterDateRange.value = "";
            }
        });
    }
});








