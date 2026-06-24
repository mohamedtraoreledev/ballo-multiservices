<?php
require '../vendor/autoload.php';

use Dompdf\Dompdf;

include "../includes/conn.php";

$idvente = $_GET["idvente"];

/* commande */
$cmd = $bd->prepare("SELECT nomclient,prenomclient,m.nom,prix_total,date_vente,couleur,dv.quantite,prix_unitaire,telephone 
FROM detail_vente as dv join vente as v on dv.id_vente=v.id join model as m on dv.id_model=m.id join couleur_models as col on dv.id_couleur=col.id
WHERE dv.id_vente=?");
$cmd->execute([$idvente]);
$reçu = $cmd->fetch(PDO::FETCH_ASSOC);
$cmd->execute([$idvente]);

$dompdf = new Dompdf();

ob_start();
?>

<h2>REÇU COMMANDE - BALLO MULTI-SERVICES</h2>

<p>Nom : <?= $reçu["nomclient"] ?> <?= $reçu["prenomclient"] ?></p>
<p>Téléphone : <?= $reçu["telephone"] ?> </p>

<table border="1" width="100%">
<tr>
    <th>Produit</th>
    <th>Couleur</th>
    <th>Quantité</th>
    <th>Prix</th>
    <!-- <th>TELEPHONE</th> -->
</tr>

<?php while($r = $cmd->fetch(PDO::FETCH_ASSOC)) { ?>
<tr>
    <td><?= $r["nom"] ?></td>
    <td><?= $r["couleur"] ?></td>
    <td><?= $r["quantite"] ?></td>
    <td><?= $r["prix_unitaire"] ?> FCFA</td>
    <!-- <td><?= $r["telephone"] ?> FCFA</td> -->
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