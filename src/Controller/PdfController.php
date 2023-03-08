<?php

namespace App\Controller;
use Dompdf\Dompdf as Dompdf;
use Dompdf\Options;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



use App\Entity\Remorquage;
use App\Entity\Service;
use App\Form\RemorquageType;
use App\Repository\RemorquageRepository;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

class PdfController extends AbstractController
{
    #[Route('/pdf', name: 'admin_remorquage_download')]
    public function index(): Response
    {

        $data = $this->getDoctrine()->getRepository(Remorquage::class)->findBy([], ['id' => 'desc']);

       

 // Configure Dompdf according to your needs
 $pdfOptions=new Options();

 $pdfOptions->set('defaultfont','Arial');
 $pdfOptions->setIsRemoteEnabled(true);
 $dompdf = new Dompdf( $pdfOptions);
 $context = stream_context_create([
     'ssl'=> [
         'verify_peer'=>  FALSE,
         'verify_peer_name'=> FALSE,
         'allow_self_signed'=> TRUE,

 ]
     ]);
     $dompdf->setHttpcontext($context);
     $html= $this->renderView('remorquage/pdf.html.twig',[ 'remorquages' => $data,]);
     $dompdf->loadhtml($html);
     $dompdf->setPaper('A4','portrait');
     $dompdf->render();
     $fichier='remorquage-data'.'pdf';

     $dompdf->stream($fichier,[
     'Attachment'=>  true
     
     
     ]);




     return new response();
      
  

    }




}
