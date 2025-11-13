class Macka {
  constructor(ime, boja, dob, spol) {
    this.ime = ime;
    this.boja = boja;
    this.dob = dob;
    this.spol = spol;
  }

  promijeniDob(novaDob) {
    this.dob = novaDob;
  }

  prikazi() {
    return `Ime: ${this.ime}, Boja: ${this.boja}, Dob: ${this.dob}, Spol: ${this.spol}`;
  }
}

let tigar = new Macka("Tigar", "narančasta", 5, "muško");
let micika = new Macka("Micika", "siva", 3, "žensko");

micika.promijeniDob(4);

document.getElementById("rezultat").innerHTML =
  "<b>Tigar:</b> " + tigar.prikazi() +
  "<br><b>Micika - nova dob:</b> " + micika.dob +
  "<h3>JSON:</h3>" + JSON.stringify(tigar) +
  "<p>Dokument zadnji put izmijenjen: " + document.lastModified + "</p>";
