// loader-script.js

document.addEventListener("DOMContentLoaded", function() {
    var forms = document.querySelectorAll("form");

    forms.forEach(function(form) {
        form.addEventListener("submit", function(event) {
            var submitBtn = form.querySelector('button[type="submit"]');
            // submitBtn.disabled = true;
            submitBtn.style.display = "none";
            // var loader = form.querySelector(".loader");
            var btnLoader = form.querySelector(".button_loader");

            btnLoader.style.display = "block";

            // Vous pouvez ajouter d'autres opérations personnalisées ici si nécessaire
            // ...

            // Vous pouvez également annuler l'événement de soumission pour empêcher le formulaire d'être soumis
            // event.preventDefault();
        });
    });
});
