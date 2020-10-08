<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
* @Route("/wild", name="wild_")
*/
Class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
    */
    public function index(): Response
    {
        return $this->render('wild/index.html.twig', [
                'title' => 'Bienvenue',
        ]);
    }

    /**
     * @Route("/show/{slug}",
     *  name="show",
     *  requirements={"slug"="^[a-z0-9-]*$"},
     *  defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"}
     * )
    */
    public function show(string $slug): Response
    {
        $slug = ucwords(str_replace("-", " ", $slug));
        return $this->render('wild/show.html.twig', [
            'slug' => $slug
        ]);
    }
}