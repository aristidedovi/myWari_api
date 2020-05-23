<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use App\Entity\Partenaire;

class ContratController extends AbstractController
{
    private $knpSnappy;

    public function __construct(Pdf $knpSnappy)
    {
        $this->knpSnappy = $knpSnappy;
    }
    /**
     * @Route("/contrat/{ninea}", name="contrat")
     */
    public function index(string $ninea)
    {

        $partenaireRepo = $this->getDoctrine()->getRepository(Partenaire::class);
        $partenaire = $partenaireRepo->findOneBy([
            "ninea" => $ninea
        ]);

        setlocale(LC_TIME, "fr_FR");
        $today = strftime("%A %d %B %G", strtotime(date("Y-m-d H:i:s")));
        $nomDeLentreprise = "WARI";

        // dd($partenaire);

        $html = $this->renderView('contrat/index.html.twig', array(
            'partenaire'  => $partenaire,
            'today' => $today,
            'nomDeLentreprise' => $nomDeLentreprise,
        ));

        return new PdfResponse(
            $this->knpSnappy->getOutputFromHtml($html),
            'file.pdf'
        );

        /* return $this->render('contrat/index.html.twig', [
            'partenaire'  => $partenaire,
            'today' => $today,
            'nomDeLentreprise' => $nomDeLentreprise,
        ]);*/
    }
}
