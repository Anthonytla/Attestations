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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $form = $this->createForm(FormType::class, $data);

        $form->handleRequest($request);
        $id = (int) $request->request->get("form")["Etudiant"];
        $etudiants = $this->getDoctrine()->getManager()->getRepository(Etudiant::class)->findAll();
    
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $data1 = $serializer->serialize($etudiants, 'json');
                
        if ($form->isSubmitted() && $form->isValid()) {

            $id = (int) $request->request->get("form")["Etudiant"];
            $etudiant = $this->getDoctrine()->getManager()->getRepository(Etudiant::class)->findBy(['id' => $id]);
            if (!$this->getDoctrine()->getManager()->getRepository(Attestation::class)->findBy(['etudiant' => $etudiant]))
                $attestation = new Attestation();
            else {
                $attestation = $this->getDoctrine()->getManager()->getRepository(Attestation::class)->findBy(['etudiant' => $etudiant])[0];
            }
            $attestation->setEtudiant($etudiant[0]);
            $attestation->setConvention($etudiant[0]->getConvention());
            $attestation->setMessage($request->request->get("form")["message"]);
            $etudiant[0]->setAttestation($attestation);
            foreach ($etudiants as $etud) {
                if ($etud === $etudiant) {
                    $etud->setAttestation($attestation);
                }
            }
            $data1 = $serializer->serialize($etudiants, 'json');
            $this->entityManager->persist($attestation);
            $this->entityManager->flush();
        }
        
        return $this->render('form/edit.html.twig', [
            'form' => $form->createView(),
            'etudiants' => $data1,
        ]);
    }
}
