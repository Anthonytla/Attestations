<?php

namespace App\Controller;

use App\Entity\Attestation;
use App\Entity\Etudiant;
use App\Form\FormEtudiantType;
use App\Form\FormType;
use App\Repository\FormRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



class FormController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/form", name="form")
     */
    public function index(): Response
    {
        return $this->render('form/index.html.twig', [
        ]);
    }

    /**
     * @Route("/form/edit", name="form_edit")
     */
    public function edit(Request $request): Response
    {
        
        $data = $request->query->all("FormType");
        
        if(isset($data["Etudiant"])){
            
            $em = $this->getDoctrine()->getManager();
            $repoCategories = $em->getRepository(Etudiant::class);
            $data["Etudiant"] = $repoCategories->findBy(['id' => $data["Etudiant"]]);
        }
        $form = $this->createForm(FormType::class, $data);
        $form->add('Etudiant', EntityType::class, [
            'class' => Etudiant::class,
            'choice_label' => 'nom',
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            /*$formu->setEtudiant($request->request->get("form")["Etudiant"]);
            $this->entityManager->persist($formu);
            $this->entityManager->flush();*/
            $id = (int) $request->request->get("form")["Etudiant"];

            return $this->redirectToRoute('form_edit_message',  array('id' => $id));
        }
        
        return $this->render('form/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/form/edit_message/{id}", name="form_edit_message")
     */

    public function edit_message(Request $request, Etudiant $etudiant) {


        $data = $request->query->all("AttestationType");
        $data["conventionName"] = $etudiant->getConvention()->getNom();
        if (!$etudiant->getAttestation()) 
            $data["Message"] = "Bonjour".' '.$etudiant->getNom().' '.$etudiant->getPrenom().".\n\n\n"."Vous avez suivi ".$etudiant->getConvention()->getNbHeur()." de formation chez FormationPlus.\n\nPouvez-vous nous retourner ce mail avec la pièce jointe signée.\n\n\nCordialement.\n\nFormationPlus.";
        else 
            $data['Message'] = $etudiant->getAttestation()->getMessage();
        $form = $this->createForm(FormEtudiantType::class, $data);
        $form->handleRequest($request);
        if (!$this->getDoctrine()->getManager()->getRepository(Attestation::class)->findBy(['etudiant' => $etudiant]))
            $attestation = new Attestation();
        else {
            $attestation = $this->getDoctrine()->getManager()->getRepository(Attestation::class)->findBy(['etudiant' => $etudiant])[0];
        }

        if ($form->isSubmitted() && $form->isValid()) {
            
            $attestation->setEtudiant($etudiant);
            $attestation->setConvention($etudiant->getConvention());
            $attestation->setMessage($request->request->get('form_etudiant')['Message']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($attestation);
            $em->flush();

            return $this->redirectToRoute('form');
        }

        return $this->render('form/edit.html.twig', [
            'form' => $form->createView(),
        ]);


    }
}
