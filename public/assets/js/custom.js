// max input
document.addEventListener("DOMContentLoaded", () => {
    const numberInput = document.getElementById("number-input");
    numberInput.addEventListener("input", () => {
        numberInput.value = numberInput.value.slice(0, 6);
    });
});

// toggle
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.querySelector(".sidebar-col");
    const toggleButton = document.querySelector(".toggle");
    const crossButton = document.querySelector(".toggle-cross");

    // Ensure the 'active' class is removed on page load
    sidebar.classList.remove("active");

    // Toggle 'active' class on toggle button click
    toggleButton.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent default link behavior
        sidebar.classList.toggle("active"); // Add or remove 'active' class
    });

    // Remove 'active' class on cross button click
    crossButton.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent default link behavior
        sidebar.classList.remove("active");
    });

    // Remove 'active' class when clicking outside sidebar and toggle button
    document.addEventListener("click", function (event) {
        if (
            !sidebar.contains(event.target) && // Click is outside sidebar
            !toggleButton.contains(event.target) && // Click is not on toggle button
            !crossButton.contains(event.target) // Click is not on cross button
        ) {
            sidebar.classList.remove("active"); // Remove 'active' class
        }
    });
});

// dropdown
// document.getElementById("userDropdown").addEventListener("click", function (e) {
//     e.preventDefault(); 
//     const dropdown = this.nextElementSibling; 
//     const isVisible = dropdown.style.display === "block";

//     dropdown.style.display = isVisible ? "none" : "block";
// });


// document.addEventListener("click", function (e) {
//     const dropdown = document.querySelector(".profile-dropdown");
//     if (
//         !e.target.closest("#userDropdown") &&
//         !e.target.closest(".profile-dropdown")
//     ) {
//         dropdown.style.display = "none";
//     }
// });


const header = document.querySelector('header'); 
const mainContent = document.querySelector('.body-content'); 

function adjustMainHeight() {
    const headerHeight = header.offsetHeight;
    mainContent.style.height = `calc(94vh - ${headerHeight}px)`;
}

window.addEventListener('resize', adjustMainHeight);
adjustMainHeight();
