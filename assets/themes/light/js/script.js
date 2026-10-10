// add bg to nav
window.addEventListener("scroll", function () {
   let scrollpos = window.scrollY;
   const header = document.querySelector("nav");
   const headerHeight = header.offsetHeight;

   if (scrollpos >= headerHeight) {
      header.classList.add("active");
   } else {
      header.classList.remove("active");
   }
});

// active nav item
const navItem = document.getElementsByClassName("nav-link");
for (const element of navItem) {
   element.addEventListener("click", () => {
      for (const ele of navItem) {
         ele.classList.remove("active");
      }
      element.classList.add("active");
   });
}

// tab
const tabs = document.getElementsByClassName("tab");
const contents = document.getElementsByClassName("content");
for (const element of tabs) {
   const tabId = element.getAttribute("tab-id");
   const content = document.getElementById(tabId);
   element.addEventListener("click", () => {
      for (const t of tabs) {
         t.classList.remove("active");
      }
      for (const c of contents) {
         c.classList.remove("active");
      }
      element.classList.add("active");
      content.classList.add("active");
   });
}

// input file preview
const previewImage = (id) => {
   document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
};

const toggleSideMenu = () => {
   document.getElementById("sidebar").classList.toggle("active");
   document.getElementById("content").classList.toggle("active");
};
const hideSidebar = () => {
   document.getElementById("formWrapper").classList.remove("active");
   document.getElementById("formWrapper2").classList.remove("active");
};

$(document).ready(function () {
   $(".js-example-basic-single").select2({
       width:'100%',
   });

    $(".js-example-basic-single2").select2({
        width:'100%',
    });

    $('.cmn-select2').select2();
    $(".modal-select").select2({
        dropdownParent: $("#formModal"),
    });

    const datepickers = document.querySelectorAll('.datepicker');
    if (datepickers.length > 0) {
        flatpickr(".datepicker", {
            disableMobile: true,
        });
    }



   // SCROLL TOP
   $(".scroll-up").fadeOut();
   $(window).scroll(function () {
      if ($(this).scrollTop() > 100) {
         $(".scroll-up").fadeIn();
      } else {
         $(".scroll-up").fadeOut();
      }
   });
});

