let html = document.querySelector("html");
let toggler = document.getElementById("display-mode");

toggler.addEventListener("click", () => {
    html.classList.toggle("dark");
});
