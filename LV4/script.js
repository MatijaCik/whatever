
script.js;


function pozdrav() {
  for (let i = 5; i > 0; i--) {
    alert("Još " + i + " upozorenja do kraja!");
  }
}

function kolegiji() {
  let kolegiji = [
    "Web programiranje",
    "OOP",
    "Matematika",
    "Fizika",
    "Baze podataka",
    "Računalne mreže",
    "Operacijski sustavi",
    "Engleski"
  ];
  document.getElementById("prezime").value = "";
  for (var broj_kolegija in kolegiji) {
    document.getElementById("prezime").value += ("Kolegij broj [" + broj_kolegija);
    document.getElementById("prezime").value += ("]: " + kolegiji[broj_kolegija] + "\n");
  }
}

function obriši() {
  document.getElementById("prezime").value = "";
}

function kvadriraj() {
  let broj = document.getElementById("broj").value.trim();
  let rezultat = document.getElementById("rezultat");

  const pattern = /^([1-9]|10)$/;
  if (!pattern.test(broj)) {
    rezultat.innerHTML = "Uneseni broj nije između 1 i 10!";
    return;
  }

  broj = parseFloat(broj);
  rezultat.innerHTML = "Kvadrat broja: " + (broj * broj);
}

function provjeri() {
  let ime = document.getElementById("ime").value.trim();
  let tekst = document.getElementById("prezime").value.trim();
  let email = document.getElementById("email").value.trim();

  if (ime === "") {
    alert("Molimo unesite ime i prezime!");
    return;
  }

  if (tekst.length <= 30) {
    alert("Tekstualno područje mora imati više od 30 znakova!");
    return;
  }

  let at = email.indexOf("@");
  let dot = email.lastIndexOf(".");
  if (at === -1) {
    alert("E-mail adresa mora sadržavati znak '@'!");
    return;
  }
  if (at < 2) {
    alert("E-mail adresa mora imati barem 2 znaka prije '@'!");
    return;
  }
  if (dot - at < 3) {
    alert("Nakon '@' moraju biti barem 2 znaka prije točke!");
    return;
  }
  if (email.length - dot < 3) {
    alert("Nakon točke moraju biti barem 2 znaka!");
    return;
  }

  let dijelovi = ime.split(" ");

  // ✔️ OBAVEZNO PREZIME
  if (dijelovi.length < 2) {
    alert("Molimo unesite i prezime!");
    return;
  }

  let imeOsobe = dijelovi[0];
  let prezimeOsobe = dijelovi.slice(1).join(" ");

  if (confirm(`Vaše ime je ${imeOsobe}, vaše prezime ${prezimeOsobe}, vaša email adresa ${email}.`)) {
    alert("Podaci su uspješno potvrđeni!");
  }
}

