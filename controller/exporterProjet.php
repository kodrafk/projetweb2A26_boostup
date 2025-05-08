<?php
require_once('../fpdf186/fpdf.php');
require_once('../config/database.php');

$pdo = getDB();
$id_projet = $_GET['id'];

$sql = "SELECT * FROM projet WHERE ID_Projet = ?";
$query = $pdo->prepare($sql);
$query->execute([$id_projet]);
$projet = $query->fetch(PDO::FETCH_ASSOC);

// Création du PDF avec orientation portrait
class PDF extends FPDF {
    // Fonction pour dessiner des rectangles avec coins arrondis
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        
        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    private function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', 
            $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, 
            $x3*$this->k, ($h-$y3)*$this->k));
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

// Couleurs personnalisées
$primaryColor = array(41, 128, 185);   // Bleu primaire
$secondaryColor = array(52, 152, 219); // Bleu secondaire
$accentColor = array(241, 196, 15);   // Jaune accent
$darkColor = array(44, 62, 80);       // Texte foncé
$lightColor = array(236, 240, 241);   // Fond clair
$successColor = array(46, 204, 113);  // Vert (pour montants)
$warningColor = array(231, 76, 60);   // Rouge (pour alertes)

// ---- En-tête stylisé ----
$pdf->SetFillColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
$pdf->Rect(0, 0, 210, 40, 'F'); // Bande bleue en haut

$pdf->SetFont('Arial', 'B', 24);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(0, 10);
$pdf->Cell(210, 10, 'FICHE  DU PROJET', 0, 0, 'C');

// Date du document
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(150, 15);
$pdf->Cell(50, 5, ' le: ' . date('d/m/Y'), 0, 0, 'R');

// ---- Titre du projet ----
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetTextColor($darkColor[0], $darkColor[1], $darkColor[2]);
$pdf->SetXY(10, 50);
$pdf->Cell(190, 10, strtoupper($projet['nom_projet']), 0, 1, 'C');

// Ligne décorative sous le titre
$pdf->SetDrawColor($accentColor[0], $accentColor[1], $accentColor[2]);
$pdf->SetLineWidth(1.5);
$pdf->Line(60, 63, 150, 63);

// ---- Section Informations ----
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
$pdf->SetXY(10, 75);
$pdf->Cell(190, 8, 'LES DETAILS DU PROJET :', 0, 1);

// Fond des informations avec coins arrondis
$pdf->SetFillColor(245, 245, 245);
$pdf->RoundedRect(10, 85, 190, 70, 5, 'FD');

// Fonction pour ajouter une ligne stylisée
function addStyledLine($pdf, $label, $value, $y, $highlight = false) {
    // Label
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(70, 70, 70);
    $pdf->SetXY(25, $y);
    $pdf->Cell(60, 8, $label, 0, 0);
    
    // Valeur
    if ($highlight) {
        $pdf->SetTextColor(231, 76, 60); // Rouge pour éléments importants
        $pdf->SetFont('Arial', 'B', 12);
    } else {
        $pdf->SetTextColor(50, 50, 50);
        $pdf->SetFont('Arial', '', 12);
    }
    
    $pdf->SetXY(85, $y);
    $pdf->Cell(0, 8, $value, 0, 1);
    
    // Ligne de séparation
    $pdf->SetDrawColor(220, 220, 220);
    $pdf->Line(25, $y + 9, 185, $y + 9);
    
    return $y + 10;
}

// Ajout des informations
$y = 88;
$y = addStyledLine($pdf, 'Date de debut', $projet['date_debut'], $y);
$y = addStyledLine($pdf, 'Date de fin', $projet['date_fin'], $y);
$y = addStyledLine($pdf, 'Montant total', number_format($projet['montant'], 2, ',', ' ') , $y);
$y = addStyledLine($pdf, 'Montant paye', number_format($projet['montant_paye'], 2, ',', ' '), $y, true);

// Calcul et affichage du reste à payer avec style
$resteAPayer = $projet['montant'] - $projet['montant_paye'];
$resteColor = ($resteAPayer > 0) ? $warningColor : $successColor;

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor($resteColor[0], $resteColor[1], $resteColor[2]);
$pdf->SetXY(25, $y);
$pdf->Cell(60, 8, 'Reste a payer:', 0, 0);
$pdf->Cell(0, 8, number_format($resteAPayer, 2, ',', ' ') , 0, 1);

// Barre de progression (visuel)
$pourcentagePaye = ($projet['montant'] > 0) ? ($projet['montant_paye'] / $projet['montant']) * 100 : 0;
$pdf->SetXY(25, $y + 15);
$pdf->SetFillColor(200, 230, 201); // Fond barre
$pdf->Cell(160, 8, '', 0, 0, 'L', true);
$pdf->SetFillColor($successColor[0], $successColor[1], $successColor[2]); // Partie remplie
$pdf->Cell(160 * ($pourcentagePaye / 100), 8, '', 0, 0, 'L', true);
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(100, 100, 100);
$pdf->SetXY(25, $y + 20);

// ---- Section Description ----
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
$pdf->SetXY(10, 170);
$pdf->Cell(190, 8, 'Description du projet', 0, 1);

// Cadre description avec coins arrondis
$pdf->SetFillColor(245, 245, 245);
$pdf->RoundedRect(10, 180, 190, 60, 5, 'FD');

// Texte description
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(60, 60, 60);
$pdf->SetXY(15, 185);
$pdf->MultiCell(180, 6, $projet['description']);

// ---- Pied de page stylisé ----
$pdf->SetY(-20);
$pdf->SetFillColor($darkColor[0], $darkColor[1], $darkColor[2]);
$pdf->Rect(0, 270, 210, 20, 'F');

$pdf->SetFont('Arial', 'I', 8);
$pdf->SetTextColor(200, 200, 200);
$pdf->SetXY(10, 273);
$pdf->Cell(0, 5, 'Document généré le ' . date('d/m/Y H:i'), 0, 0, 'L');
$pdf->SetXY(0, 273);
$pdf->Cell(0, 5, 'Page ' . $pdf->PageNo(), 0, 0, 'C');
$pdf->SetXY(0, 273);
$pdf->Cell(0, 5, 'Projet ID: ' . $id_projet, 0, 0, 'R');

$pdf->Output('Projet_' . $projet['nom_projet'] . '.pdf', 'I');
?>