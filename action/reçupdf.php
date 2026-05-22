<?php
require '../vendor/autoload.php';

use Dompdf\Dompdf;

include "../includes/conn.php";

$idCommande = $_GET["idCommande"];

/* commande */
$cmd = $bd->prepare("SELECT c.nom, c.prenom, co.prix_total 
FROM commande co 
JOIN client c ON co.id_client=c.id 
WHERE co.id=?");
$cmd->execute([$idCommande]);
$reçu = $cmd->fetch(PDO::FETCH_ASSOC);

/* details */
$details = $bd->prepare("
SELECT m.nom, description, cd.quantite, cd.prix, col.couleur
FROM commande_details cd
JOIN model m ON cd.id_produit=m.id
JOIN couleur_models col ON cd.id_couleur=col.id
WHERE cd.id_commande=?
");
$details->execute([$idCommande]);

$dompdf = new Dompdf();

ob_start();
?>

<h2>REÇU COMMANDE - BALLO MULTI-SERVICES</h2>

<p>Nom : <?= $reçu["nom"] ?> <?= $reçu["prenom"] ?></p>

<table border="1" width="100%">
<tr>
    <th>Produit</th>
    <th>Couleur</th>
    <th>Quantité</th>
    <th>Prix</th>
</tr>

<?php while($r = $details->fetch(PDO::FETCH_ASSOC)) { ?>
<tr>
    <td><?= $r["nom"] ?></td>
    <td><?= $r["couleur"] ?></td>
    <td><?= $r["quantite"] ?></td>
    <td><?= $r["prix"] ?> FCFA</td>
</tr>
<?php } ?>

</table>

<h3>Total : <?= $reçu["prix_total"] ?> FCFA</h3>

<?php
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("recu_commande.pdf", ["Attachment" => true]);
?>