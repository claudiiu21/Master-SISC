
// script for index.html

function loadProducts() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'produse.php', true);  // deschidem .php si facem GET pe el
    // GET e folosit ca sa preluam date de la sursa

    xhr.onload = function () {
        console.log("XHR Status:", xhr.status);  
        // status 200 ==> success 
        if (xhr.status === 200) {
            try {
                var produse = JSON.parse(xhr.responseText);  // verificam ca datele vin corect pe JSON
                console.log("Loaded Products:", produse);  

                var produseList = '';

                // scriem caracteristiciile pentru fiecare telefon (cand card-ul e micut)
                // also punem tot in lowercase ca sa ne fie si mai usor la filtrare pe baza numelui
                produse.forEach(function (produs) {
                    produseList += '<div class="produs-card" data-name="' + produs.nume.toLowerCase() + '">';
                    produseList += '<h4>' + produs.nume + '</h4>';
                    produseList += '<p><strong>ID:</strong> ' + produs.id + '</p>';
                    produseList += '<p><strong>Pret:</strong> ' + produs.pret + ' RON</p>';
                    produseList += '<p><strong>An fabricare:</strong> ' + produs.an_fabricare + '</p>';

                    // caracteristici cand apasam pe telefon
                    produseList += '<div class="detalii-produs">';
                    produseList += '<p><strong>RAM:</strong> ' + produs.ram + ' GB</p>';
                    produseList += '<p><strong>VRAM:</strong> ' + produs.vram + ' GB</p>';
                    produseList += '<p><strong>Procesor:</strong> ' + produs.procesor + '</p>';
                    produseList += '<p><strong>Display:</strong> ' + produs.display + ' inches</p>';
                    produseList += '<p><strong>Camera:</strong> ' + produs.camera + ' MP</p>';
                    produseList += '</div>';
                    produseList += '</div>';
                });

                // trimitem lista mai departe
                document.getElementById('produse-list').innerHTML = produseList;

                // event-uri de click in caz ca se apasa pe card -> vrem sa il facem mare
                var produseCards = document.querySelectorAll('.produs-card');
                produseCards.forEach(function (card) {
                    card.addEventListener('click', function () {
                        // facem card-ul mare
                        card.classList.toggle('mare');
                    });
                });

            } catch (e) {
                console.error("Error parsing JSON:", e);
            }
        } else {
            console.error('Failed to load products, status:', xhr.status);
        }
    };
    xhr.send();
}

// facem search-ul dinamic dupa nume
document.getElementById('search-input').addEventListener('input', function (event) {
    var searchTerm = event.target.value.toLowerCase();  
    var produseCards = document.querySelectorAll('.produs-card');  

    // trecem prin fiecare telefon
    produseCards.forEach(function (card) {
        var productName = card.getAttribute('data-name');  

        // aici se intampla filtrarea propriuzisa -> daca are elemente afisam, daca nu are nu afisam 
        if (productName.includes(searchTerm)) {
            card.style.display = '';  // afisare
        } else {
            card.style.display = 'none';  // else ascunde/nu afisa
        }
    });
});

window.onload = loadProducts;
