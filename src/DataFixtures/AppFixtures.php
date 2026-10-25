<?php

namespace App\DataFixtures;

use App\Entity\Campagne;
use App\Entity\Cotisation;
use App\Entity\Groupement;
use App\Entity\Membre;
use App\Entity\ModeCotisation;
use App\Entity\Role;
use App\Entity\Seance;
use App\Entity\TypeCampagne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $roles = [];
        foreach (['Admin', 'Trésorier', 'Secrétaire', 'Membre simple'] as $nom) {
            $role = (new Role())->setNom($nom);
            $manager->persist($role);
            $roles[$nom] = $role;
        }

       
        $types = [];
        foreach (['Épargne', 'Tontine'] as $nom) {
            $type = (new TypeCampagne())->setNom($nom);
            $manager->persist($type);
            $types[] = $type;
        }

        
        $groupements = [];
        foreach (['Groupement Teranga', 'Groupement Yaakaar'] as $nom) {
            $groupement = (new Groupement())->setNom($nom);
            $manager->persist($groupement);
            $groupements[] = $groupement;
        }

       
        $noms = [
            'Awa Ndiaye', 'Fatou Diop', 'Aminata Sow', 'Mariama Ba', 'Khady Fall', 'Aïssatou Sarr',
            'Ndèye Gueye', 'Rokhaya Diouf', 'Coumba Faye', 'Sokhna Mbaye', 'Dieynaba Thiam', 'Adama Seck',
        ];
        $quartiers = ['Parcelles Assainies', 'Pikine', 'Guédiawaye', 'Médina', 'Yoff', 'Thiaroye'];
        $rolesParPosition = [0 => 'Admin', 1 => 'Trésorier', 2 => 'Secrétaire'];
        $motDePasse = password_hash('password', PASSWORD_BCRYPT);

        $membres = [[], []];
        foreach ($noms as $i => $nomComplet) {
            $g = intdiv($i, 6);
            $position = $i % 6;
            $membre = (new Membre())
                ->setNomComplet($nomComplet)
                ->setTelephone('77' . mt_rand(1000000, 9999999))
                ->setAdresse($quartiers[$position])
                ->setLogin('membre' . ($i + 1))
                ->setPassword($motDePasse)
                ->setRole($roles[$rolesParPosition[$position] ?? 'Membre simple'])
                ->setGroupement($groupements[$g]);
            $manager->persist($membre);
            $membres[$g][] = $membre;
        }

        // Campagnes, modes de cotisation, séances et cotisations
        foreach ($groupements as $g => $groupement) {
            foreach ($types as $type) {
                $estTontine = $type->getNom() === 'Tontine';

                $campagne = (new Campagne())
                    ->setNom($type->getNom() . ' - ' . $groupement->getNom())
                    ->setDateDebut(new \DateTime('2026-09-07'))
                    ->setDateFin($estTontine ? new \DateTime('2026-12-28') : null)
                    ->setMontant($estTontine ? 10000.0 : 5000.0)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setTypeCampagne($type)
                    ->setGroupement($groupement);
                $manager->persist($campagne);

                $libres = [];
                foreach ($membres[$g] as $m => $membre) {
                    $libre = !$estTontine && $m % 3 === 0;
                    $mode = (new ModeCotisation())
                        ->setIsLibre($libre)
                        ->setCampagne($campagne)
                        ->setMembre($membre);
                    $manager->persist($mode);
                    $libres[$m] = $libre;
                }

                for ($k = 0; $k < 4; $k++) {
                    $date = (new \DateTime('2026-09-07'))->modify('+' . $k . ($estTontine ? ' week' : ' month'));
                    $seance = (new Seance())
                        ->setDateSeance($date)
                        ->setFrequence($estTontine ? 'hebdomadaire' : 'mensuelle')
                        ->setCampagne($campagne);
                    $manager->persist($seance);

                    foreach ($membres[$g] as $m => $membre) {
                        $montant = $libres[$m] ? mt_rand(2, 10) * 500.0 : $campagne->getMontant();
                        $cotisation = (new Cotisation())
                            ->setMontant($montant)
                            ->setDatePaiement(\DateTimeImmutable::createFromInterface($date))
                            ->setSeance($seance)
                            ->setMembre($membre);
                        $manager->persist($cotisation);
                    }
                }
            }
        }

        $manager->flush();
    }
}