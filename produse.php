<?php
include 'config.php'; // Include fișierul de configurare pentru conexiunea la baza de date

// adaugare produs - cerere de tip POST deoarece vrem sa scriem la nivel de obiect, nu doar sa citim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    // Preluăm datele din formular
    $nume = $_POST['nume'];
    $pret = $_POST['pret'];
    $an_fabricare = $_POST['an_fabricare'];
    $ram = $_POST['ram']; 
    $vram = $_POST['vram'];
    $procesor = $_POST['procesor'];
    $display = $_POST['display'];
    $camera = $_POST['camera'];

    // Verificăm dacă toate câmpurile sunt completate
    if (empty($nume) || empty($ram) || empty($vram) || empty($procesor) || empty($display) || empty($camera)) {
        echo "Eroare: Toate câmpurile sunt obligatorii!";
        exit;
    }

    // comanda SQL o luam si o executam (insert obiect nou in tabelul produse)
    $sql = "INSERT INTO produse
            nume = ?, 
            pret = ?, 
            an_fabricare = ?, 
            ram = ?, 
            vram = ?, 
            procesor = ?, 
            display = ?, 
            camera = ? 
        WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nume, $pret, $an_fabricare, $ram, $vram, $procesor, $display, $camera])) {
        // alerta de tip html pentru succes
        echo "<script>alert('Telefonul a fost adăugat cu succes!'); window.location.href='index.html';</script>";
        exit;
    } else {
        echo "Eroare la adăugarea telefonului!";
        exit;
    }
}

// update produs - nevoie de POST, vrem sa scriem la nivel de obiect nu doar sa citim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nume = $_POST['nume'];
    $pret = $_POST['pret'];
    $an_fabricare = $_POST['an_fabricare'];
    $ram = $_POST['ram']; 
    $vram = $_POST['vram'];
    $procesor = $_POST['procesor'];
    $display = $_POST['display'];
    $camera = $_POST['camera'];

    // first of all, trebuie sa verificam daca avem deja un ID existent in database sau nu
    if (empty($id)) {
        echo "Eroare: ID-ul telefonului nu este valid!";
        exit;
    }

    // comanda SQL
    $sql = "UPDATE produse SET 
                nume = ?, 
                pret = ?, 
                an_fabricare = ?, 
                ram = ?, 
                vram = ?, 
                procesor = ?, 
                display = ?, 
                camera = ? 
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nume, $pret, $an_fabricare, $ram, $vram, $procesor, $display, $camera, $id])) {
        // trimitem alerta de succes de tip html
        echo "<script>alert('Telefonul a fost actualizat cu succes!'); window.location.href='index.html';</script>";
        exit;
    } else {
        echo "Eroare la actualizarea telefonului!";
        exit;
    }
}

// stergem produs -> nevoie de POST 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];

    if (empty($id)) {
        echo "Eroare: ID-ul telefonului nu este valid!";
        exit;
    }

    // daca avem un obiect valid, cream comanda SQL pe care o vom rula mai tarziu
    $sql = "DELETE FROM produse WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$id])) {
        echo "<script>alert('Telefonul a fost șters cu succes!'); window.location.href='index.html';</script>";
        exit;
    } else {
        echo "Eroare la ștergerea telefonului!";
        exit;
    }
}

// pentru pagina index, vrem doar sa vizualizam lista => nevoie de GET nu de POST, care modifica la nivel de obiect
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // facem comanda SQL -> select all from tabel
    $sql = "SELECT * FROM produse";
    $stmt = $pdo->query($sql);
    $produse = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // returnam în format JSON
    echo json_encode($produse);
}
?>
