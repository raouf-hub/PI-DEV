<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Entity\Voiture;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use App\Repository\VoitureRepository;
use App\Service\SendMailService;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Lexer\Token;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Flex\Options as FlexOptions;
use Stripe\Stripe;
#[Route('/contrat')]
class ContratController extends AbstractController
{
    #[Route('/', name: 'app_contrat_index', methods: ['GET'])]
    public function index(ContratRepository $contratRepository): Response
    {
        
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contratRepository->findAll(),
        ]);

    }
    
    #[Route('/frontContrat', name: 'app_contrat_indexfront', methods: ['GET'])]
    public function indexC(ContratRepository $contratRepository): Response
    {
        return $this->render('front/indexContrat.html.twig', [
            'contrats' => $contratRepository->findAll(),
        ]);
    }
    #[Route('/contratmobile', name: 'app_contratmobile', methods: ['GET'])]
    public function contratmob(ContratRepository $contratRepository,SerializerInterface $serializerInterface): Response
    {
        $contrats =$contratRepository->findAll();
        $json=$serializerInterface->serialize($contrats,'json');
return new Response($json);

    }

    
    #[Route('/new', name: 'app_contrat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContratRepository $contratRepository,SluggerInterface $slugger,SendMailService $mail): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);
        
        $test=$request->get('contrat');
        if ($form->isSubmitted() && $form->isValid()) {
            $currentDate = new DateTime();
            $currentDate->format('Y-m-d ');
            $contrat->setDateDeb($currentDate);
            // $currentDate = new DateTimeImmutable();
            //     $currentDate->format('Y-m-d H:i:s');
            //     $currentDate->add(new DateInterval('P1D'));
            //     dd($currentDate);
            $date = new DateTime();
             $date->add(new \DateInterval('P'.$test['periode']));
            $contrat->setDateFin($date);
// partie image
$image = $form->get('image')->getData();
            
            

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('contrat_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $contrat->setimage($newFilename);
            }
            $contratRepository->save($contrat, true);
            /**************user */
            $email=$request->get('email');
            /******** */
            $mail->send(
            'aymen.benbrahim@esprit.tn',
            $email,
            'Contrat',
            'contrat/listcontrat.html.twig',
            [
                'contrat'=>$contrat
            ]
            
        );

            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);




         
        }

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/front/newcontrat', name: 'app_contrat_new_front', methods: ['GET', 'POST'])]
    public function newC(Request $request, ContratRepository $contratRepository,SluggerInterface $slugger,SendMailService $mail): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);
        
        $test=$request->get('contrat');
        if ($form->isSubmitted() && $form->isValid()) {
            $currentDate = new DateTime();
            $currentDate->format('Y-m-d ');
            $contrat->setDateDeb($currentDate);
            // $currentDate = new DateTimeImmutable();
            //     $currentDate->format('Y-m-d H:i:s');
            //     $currentDate->add(new DateInterval('P1D'));
            //     dd($currentDate);
            $date = new DateTime();
             $date->add(new \DateInterval('P'.$test['periode']));
            $contrat->setDateFin($date);
            // partie image
            $image = $form->get('image')->getData();
            
            

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('contrat_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $contrat->setimage($newFilename);
            }
            $contratRepository->save($contrat, true);
            /**************user */
            $email=$request->get('email');
            /******** */
            $mail->send(
            'aymen.benbrahim@esprit.tn',
            $email,
            'Contrat',
            'contrat/listcontrat.html.twig',
            [
                'contrat'=>$contrat
            ]
            
        );

            return $this->redirectToRoute('app_contrat_indexfront', [], Response::HTTP_SEE_OTHER);




         
        }

        return $this->renderForm('front/newContrat.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    /************************mobil supp*************************************** */
    #[Route('/suppMobil/{id}', name: 'app_contrat_suppMobil', methods: ['POST','GET'])]
    public function suppMobil(Contrat $contrat, ContratRepository $contratRepository,SerializerInterface $serializerInterface): Response
    {

            $contratRepository->remove($contrat, true);
            $json=$serializerInterface->serialize($contrat,'json');
    return new Response("Contrat deleted successfully" .$json);
    }
    
    /*****************************add mobil**************************************** */
    #[Route('/addMobil', name: 'app_contrat_addMobil', methods: ['GET', 'POST'])]
    public function addMobil(Request $request, ContratRepository $contratRepository,SerializerInterface $serializerInterface,VoitureRepository $voitureRepository)
    {
        $currentDate = new DateTime();
        $currentDate->format('Y-m-d ');
  
        $date = new DateTime();
         $date->add(new \DateInterval('P'.$request->get('periode')));
        $contrat= new Contrat();
        $contrat->setDateDeb($currentDate);
        $contrat->setDateFin($date);
        $contrat->setTypeDeContrat($request->get('type'));
        $contrat->setImage($request->get('image'));

        $voiture=new Voiture();
        $voiture=$voitureRepository->findOneByMatricule($request->get('mat'));

        $contrat->setMatricule($voiture);
        $contratRepository->save($contrat,true);
        $json=$serializerInterface->serialize($contrat,'json');
        return new Response("Contrat added successfully" .$json);
        


    }

    #[Route('/{id}/pdf', name: 'app_contrat_pdf')]

        public function generatePdfAction($id, ContratRepository $cr)
        {
            // Récupérez les informations de la commande correspondant à l'ID
            $contrat = $cr->findContratById($id);
            
            // Générez le contenu HTML à partir des informations de la commande
            $html = $this->renderView('contrat/listcontrat.html.twig', ['contrat' => $contrat]);
    
            // Générez le PDF à partir du contenu HTML
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfContent = $dompdf->output();
            
            // Renvoyez le PDF en tant que réponse HTTP
            $response = new Response();
            $response->headers->set('Content-Type', 'application/pdf');
            $response->setContent($pdfContent);
            return $response;
        }
        // $pdfOptions = new Options();
        // $pdfOptions->set('defaultFont', 'Arial');
        
        // // Instantiate Dompdf with our options
        // $dompdf = new Dompdf($pdfOptions);
        // $contrat =$contratRepository->findAll();
 
       
        
        // // Retrieve the HTML generated in our twig file
        // $html = $this->renderView('contrat/listcontrat.html.twig', [
        //     'contrat' => $contrat,
            
        // ]);
        
        // // Load HTML to Dompdf
        // $dompdf->loadHtml($html);
        
        // // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        // $dompdf->setPaper('A4', 'portrait');

        // // Render the HTML as PDF
        // $dompdf->render();

        // // Output the generated PDF to Browser (force download)
        // $dompdf->stream("mypdf.pdf", [
        //     "Attachment" => true
        // ]);
     
    

    #[Route('/{id}', name: 'app_contrat_show', methods: ['GET'])]
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }
///////////////////////////////////////////////////////////paiment//////////////////
 function initStripe(): Token
  {
  Stripe::setApiKey('pk_test_51MiGlsDOK8juJEL07m1yYciuBVmCbL3QNDYk9JyeuOnem7pgy19pBtuIuuisFeaDXBUPLEctx621jqJV2S58szWX00RXvaGDtk');
  // Créez un jeton de carte test
  $cardToken = Token::create([
  'card' => [
  'number' => '4242424242424242',
  'exp_month' => 1,
  'exp_year' => date('Y') + 1,
  'cvc' => '123',
  ],
  ]);

 return $cardToken;
}

 #[Route('/payment', name:'app_process_payment', methods:['POST', 'GET'])]
 function processPayment(Request $request, contratRepository $ContratRepository, SessionInterface $session,ManagerRegistry $mr): Response
  {
  $user=$this->getUser();
  // Initialiser la bibliothèque Stripe avec la clé d'API et le jeton de carte test
  $stripeToken = $this->initStripe();

  $ContratData = new Contrat();
  $ContratData = $session->get('abonnementData');
  // dd($abonnementData);
  // $amount=$abonnementData->getOffre()->getPrix();

  // Récupérer les détails du paiement à partir du formulaire
  $amount = $request->request->get('amount');
 
  $cardTokenFromForm = $request->request->get('card_token');
  $email = $user->getUsername();
  $metadata = [
  'email' => $email,
  ];
 

 $mpayement = $ContratData->getMPayement();
 $dateD = $ContratData->getDateDeb();
 $ContratData->setEtatab(true);
  // Créer la charge Stripe
  try {
  $charge = charge::create([
  'amount' => $amount * 100, // le montant doit être en centimes
  'currency' => 'eur',
  'source' => $stripeToken->id, // utilisez le jeton de carte
  'metadata' => $metadata,
 ]);

//  // Paiement réussi

 
  $id=$ContratData->getId();
 $ContratRepository->changeEtat(true,$id);
  // $response = new JsonResponse(['status' => 'success']);
  $this->addFlash('success','payment affected');
  return $this->redirectToRoute('app_profil');

  } catch (\Exception$e) {
  // Erreur de paiement
  // $response = new JsonResponse(['status' => 'error', 'message' => $e->getMessage()]);
  }

  // Afficher l'alerte de paiement réussi ou d'erreur
  return $this->render('front/payment.html.twig', [
  // 'paymentResponse' => $response->getContent(),
  'cardToken' => $cardTokenFromForm,
  'prix'=>$ContratData->getOffre()->getPrix()
  ]);
}
/////////////////////////////////////////////////////////////////////////////////////////

    
    #[Route('/{id}/edit', name: 'app_contrat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contratRepository->save($contrat, true);

            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_delete', methods: ['POST'])]
    public function delete(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
            $contratRepository->remove($contrat, true);
        }

        return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
    }
   
}
