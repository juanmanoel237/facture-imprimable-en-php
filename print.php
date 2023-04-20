<?php
require("config/consts.php");
require("fpdf/fpdf.php");

//customer and invoice details
$info = [
    "customer" => "",
    "address" => "",
    "city" => "",
    "invoice_no" => "",
    "invoice_date" => "",
    "total_amt" => "",
    "words" => "",
];

$sql = "SELECT * FROM facture WHERE SID='{$_GET["id"]}'";
$res = $connex->query($sql);
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $info = [
        "customer" => $row["CNOM"],
        "address" => $row["CADRESSE"],
        "city" => $row["CVILLE"],
        "invoice_no" => $row["NUM_FACTURE"],
        "invoice_date" => date("d-m-y", strtotime($row["DATE_FACTURE"])),
        "total_amt" => $row["GRAND_TOTAL"],
        "words" => "",
    ];
}


//invoice Products
$products_info = [];
$sql = "SELECT * FROM facture_produits WHERE SID='{$_GET["id"]}'";
$res = $connex->query($sql);
if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $products_info[] = [
            "nom" => $row["PNOM"],
            "prix" => $row["PRIX"],
            "qté" => $row["QTY"],
            "total" => $row["TOTAL"]
        ];
    }
}



class PDF extends FPDF
{
    function Header()
    {

        //Afficher les infos de la compagnie
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(50, 10, "ABC COMPUTERS", 0, 1);
        $this->SetFont('Arial', '', 14);
        $this->Cell(50, 7, "Route des Petits Ponts,", 0, 1);
        $this->Cell(50, 7, "Paris 75019.", 0, 1);


        //Afficher le texte de la facture
        $this->SetY(15);
        $this->SetX(-40);
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(50, 10, "FACTURE", 0, 1);

        //Lignes Horizontales
        $this->Line(0, 48, 210, 48);
    }

    function body($info, $products_info)
    {

        //Détails facture
        $this->SetY(55);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(50, 10, "Facture Pour: ", 0, 1);
        $this->SetFont('Arial', '', 12);
        $this->Cell(50, 7, $info["customer"], 0, 1);
        $this->Cell(50, 7, $info["address"], 0, 1);
        $this->Cell(50, 7, $info["city"], 0, 1);

        //Afficher le numéro de facture
        $this->SetY(55);
        $this->SetX(-60);
        $this->Cell(50, 7, "No Facture : " . $info["invoice_no"]);

        //Afficher la date de facturation
        $this->SetY(63);
        $this->SetX(-60);
        $this->Cell(50, 7, "Date Facture : " . $info["invoice_date"]);

        //Afficher les en-têtes du tableau
        $this->SetY(95);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(80, 9, "DESCRIPTION", 1, 0);
        $this->Cell(40, 9, "PRICE", 1, 0, "C");
        $this->Cell(30, 9, "QTY", 1, 0, "C");
        $this->Cell(40, 9, "TOTAL", 1, 1, "C");
        $this->SetFont('Arial', '', 12);

        //Afficher les lignes de produits du tableau
        foreach ($products_info as $row) {
            $this->Cell(80, 9, $row["nom"], "LR", 0);
            $this->Cell(40, 9, $row["prix"], "R", 0, "R");
            $this->Cell(30, 9, $row["qté"], "R", 0, "C");
            $this->Cell(40, 9, $row["total"], "R", 1, "R");
        }
        //Afficher les lignes vides du tableau
        for ($i = 0; $i < 12 - count($products_info); $i++) {
            $this->Cell(80, 9, "", "LR", 0);
            $this->Cell(40, 9, "", "R", 0, "R");
            $this->Cell(30, 9, "", "R", 0, "C");
            $this->Cell(40, 9, "", "R", 1, "R");
        }
        //Afficher la ligne du total du tableau
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(150, 9, "TOTAL", 1, 0, "R");
        $this->Cell(40, 9, $info["total_amt"], 1, 1, "R");

        //Afficher la somme
        $this->SetY(225);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 9, "", 0, 1);
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 9, $info["words"], 0, 1);
    }
    function Footer()
    {

        //footer position
        $this->SetY(-50);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, "Pour ABC COMPUTERS", 0, 1, "R");
        $this->Ln(15);
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, "Signature", 0, 1, "R");
        $this->SetFont('Arial', '', 10);

        //Footer Text
        $this->Cell(0, 10, "Ceci est une facture générée par ordinateur", 0, 1, "C");
    }
}
//Créer une page A4 
$pdf = new PDF("P", "mm", "A4");
$pdf->AddPage();
$pdf->body($info, $products_info);
$pdf->Output();
