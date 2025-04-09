<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stopper és szövegbemenet</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #stopper {
            display: inline-block;
            margin-left: 10px;
        }
        #eredmeny {
            margin-top: 20px;
        }
        textarea[disabled] {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Stopper és szövegbemenet</h1>
        <form>
            <div class="form-group">
                <label for="nev">Név:</label>
                <input type="text" class="form-control" id="nev" name="nev" oninput="sanitizeInput(this)">
            </div>
            <div class="form-group">
                <label for="tema">Téma:</label>
                <input type="text" class="form-control" id="tema" name="tema" oninput="sanitizeInput(this)">
            </div>
            <button type="button" class="btn btn-primary" id="start" disabled>Start</button>
            <span id="stopper">00:00</span>
            <div class="form-group mt-3">
                <label for="szoveg">Szöveg:</label>
                <textarea class="form-control" id="szoveg" rows="10" disabled></textarea>
            </div>
            <button type="button" class="btn btn-success" id="kesz">KÉSZ</button>
        </form>
        <p id="eredmeny" class="mt-4"></p>
    </div>

    <script>
        const nevInput = document.getElementById('nev');
        const temaInput = document.getElementById('tema');
        const startButton = document.getElementById('start');
        const stopper = document.getElementById('stopper');
        const szovegInput = document.getElementById('szoveg');
        const keszButton = document.getElementById('kesz');
        const eredmeny = document.getElementById('eredmeny');

        let interval;
        let elapsedTime = 0;

        function checkInputs() {
            startButton.disabled = !(nevInput.value.trim() && temaInput.value.trim());
        }

        function sanitizeInput(input) {
            input.value = input.value.replace(/(<([^>]+)>|<|>|<\?php|\?|<?|sql|SELECT|INSERT|UPDATE|DELETE|DROP)/gi, '');
        }

        nevInput.addEventListener('input', checkInputs);
        temaInput.addEventListener('input', checkInputs);

        startButton.addEventListener('click', () => {
            clearInterval(interval);
            elapsedTime = 0;
            interval = setInterval(() => {
                elapsedTime++;
                const minutes = Math.floor(elapsedTime / 60).toString().padStart(2, '0');
                const seconds = (elapsedTime % 60).toString().padStart(2, '0');
                stopper.textContent = `${minutes}:${seconds}`;
            }, 1000);
            szovegInput.disabled = false;
            szovegInput.focus();
        });

        keszButton.addEventListener('click', () => {
            clearInterval(interval);
            const nev = nevInput.value.trim();
            const tema = temaInput.value.trim();
            const szoveg = szovegInput.value.trim();
            const datum = new Date().toLocaleString();
            const gepeltIdo = stopper.textContent;
            const formatedNev = nev.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, '_');
            const formatedTema = tema.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, '_');
            const fileName = `${formatedNev}-${formatedTema}-${datum.replace(/[^0-9]/g, '')}-${gepeltIdo.replace(':', '')}.txt`;
            eredmeny.innerHTML = `<strong>Név:</strong> ${nev}<br><strong>Téma:</strong> ${tema}<br><strong>Dátum:</strong> ${datum}<br><strong>Gépelt idő:</strong> ${gepeltIdo}<br><strong>Szöveg:</strong> ${szoveg}`;
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'mentes.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(`fileName=${fileName}&nev=${encodeURIComponent(nev)}&tema=${encodeURIComponent(tema)}&datum=${encodeURIComponent(datum)}&gepeltIdo=${encodeURIComponent(gepeltIdo)}&szoveg=${encodeURIComponent(szoveg)}`);
        });

        // Megakadályozzuk a beillesztést a szöveg beviteli mezőbe
        szovegInput.addEventListener('paste', (event) => {
            event.preventDefault();
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
