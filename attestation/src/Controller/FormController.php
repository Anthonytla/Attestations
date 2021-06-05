<?php

namespace App\Controller;

use App\Entity\Attestation;
use App\Entity\Etudiant;
use App\Form\FormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     *@Route("/api/etudiant/{id}", name="api_etudiant") 
     */
    public function getEtudiant(int $id) 
    {
        $etudiant = $this->getDoctrine()->getRepository(Etudiant::class)->findBy(['id' => $id])[0];
        return $this->json($etudiant, 200, [], ['groups' => 'etudiant']);
    }

    /**
     *@Route("/api/convention/{id}", name="api_convention") 
     */
    public function getConvention(int $id)
    {
        return $this->json($this->getDoctrine()->getRepository(Etudiant::class)->findBy(['id' => $id])[0]->getConvention(), 200, [], ['groups' => 'convention']);
    }

    /**
     * @Route("/form/store", name="store")
     */
    public function store(Request $request) 
    {

        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $id = (int) $request->request->get("form")["Etudiant"];
            $etudiant = $this->getDoctrine()->getManager()->getRepository(Etudiant::class)->findBy(['id' => $id]);
            if (!$this->getDoctrine()->getManager()->getRepository(Attestation::class)->findBy(['etudiant' => $etudiant]))
                $attestation = new Attestation();
            else 
                $attestation = $this->getDoctrine()->getManager()->getRepository(Attestation::class)->findBy(['etudiant' => $etudiant])[0];
            $attestation->setMessage($request->request->get("form")["message"]);
            $etudiant[0]->setAttestation($attestation);

            $this->entityManager->persist($attestation);
            $this->entityManager->flush();
        }
        return $this->render('form/edit.html.twig', [
            'form' => $this->createForm(FormType::class)->createView(),
        ]);
    }
}
