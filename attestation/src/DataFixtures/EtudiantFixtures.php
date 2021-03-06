<?php

namespace App\DataFixtures;

use App\Entity\Convention;
use App\Entity\Etudiant;
use App\Entity\Form;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtudiantFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $convention = new Convention();
        $convention->setNom("convention1");
        $convention->setNbHeur(0);
        
        $manager->persist($convention);

        $convention2 = new Convention();
        $convention2->setNom("convention2");
        $convention2->setNbHeur(20);
        
        $manager->persist($convention2);

        
        $etudiant1 = new Etudiant();
        $etudiant1->setNom("Dupont");
        $etudiant1->setPrenom("Olivier");
        $etudiant1->setMail("dupont_o@gmail.com");
        $etudiant1->setConvention($convention);

        $etudiant2 = new Etudiant();
        $etudiant2->setNom("Dupuis");
        $etudiant2->setPrenom("Alice");
        $etudiant2->setMail("dupuis_a@gmail.com");
        $etudiant2->setConvention($convention);

        $etudiant3 = new Etudiant();
        $etudiant3->setNom("Du");
        $etudiant3->setPrenom("Alice");
        $etudiant3->setMail("du_a@gmail.com");
        $etudiant3->setConvention($convention2);

        $convention->addEtudiant($etudiant1);
        $convention->addEtudiant($etudiant2);
        $convention2->addEtudiant($etudiant3);

        $form = new Form();
        $form->setMessage("");
        $form->setConventionName("");
        $form->setEtudiant("");
        $manager->persist($form);
        $manager->persist($etudiant1);
        $manager->persist($etudiant2);
        $manager->persist($etudiant3);
        

        $manager->flush();
    }
}
