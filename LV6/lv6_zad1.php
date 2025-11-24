<?php


$student = [
    "Ime" => "Matija",
    "Prezime" => "Cikojević",
    "JMBG" => "0035246358",
    "Broj_indeksa" => "0035246358",
    "Godina_studija" => 3 . "."
];

echo "<h2>Student:</h2>";

foreach($student as $el) {
    echo $el . "<br>";
}


$auto = [
    "Citroen" => [
        "tip_automobila" => "C4",
        "kubikaza" => "2500",
        "boja" => "sivi",
        "godina_proizvodnje" => 2021,
        "motor" => "diesel"
    ],
    "Mercedes" => [
        "tip_automobila" => "C200",
        "kubikaza" => "2012",
        "boja" => "bijela",
        "godina_proizvodnje" => 2019,
        "motor" => "benzin"
    ]
];

echo "<h2>Citroen:</h2>";
foreach($auto["Citroen"] as $key => $val) {
    echo $key . ": " . $val . "<br>";
}

echo "<h2>Mercedes:</h2>";
echo "Tip automobila: " . $auto["Mercedes"]["tip_automobila"] . "<br>";
echo "Kubikaza: " . $auto["Mercedes"]["kubikaza"] . "<br>";
echo "Boja: " . $auto["Mercedes"]["boja"] . "<br>";
echo "Godina proizvodnje: " . $auto["Mercedes"]["godina_proizvodnje"] . "<br>";
echo "Motor: " . $auto["Mercedes"]["motor"] . "<br>";


function kvadrat($broj) {
    return $broj * $broj;
}

echo "<h2>Kvadrat broja 5 je: " . kvadrat(5) . "</h2>";


class Kupac {
    private $ime;
    private $prezime;

    public function setIme($ime) {
        $this->ime = $ime;
    }

    public function setPrezime($prezime) {
        $this->prezime = $prezime;
    }

    public function ispis() {
        echo "Kupac je: " . strtoupper($this->ime) . " " . strtoupper($this->prezime);
    }
}

echo "<h2>Kupac:</h2>";

$k = new Kupac();
$k->setIme("Matija");
$k->setPrezime("Cikojević");
$k->ispis();

?>
