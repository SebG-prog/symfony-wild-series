<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
* @Route("/wild", name="wild_")
*/
Class WildController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     * 
     * @Route("/", name="index")
     * @return Response
     * 
    */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException('No program found in program\'s table.');            
        }

        return $this->render(
            'wild/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * Getting a program with a formatted slug for title
     * 
     * @param string $slug The slugger
     * @Route("/show/{slug}",
     *  name="show",
     *  requirements={"slug" = "^[a-z0-9-]+$"},
     *  defaults={"slug" = null}
     * )
     * @return Response
     * 
    */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this->createNotFoundException(
                'No slug has been sent to find a program in program\'s table'
            );
        }
        $slug = preg_replace(
            '/-/',
            ' ',
            ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]); // findOneByTitle(mb_strtolower($slug))
        
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }
        
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug
        ]);
    }
    /**
     * 
     *  
     * @param string $categoryName Name of the category
     * @Route("/category/{categoryName}",
     *  name="show_category",
     *  requirements={"categoryName" = "^[a-zA-Z]+$"}
     * )
     * @return Response
     * 
    */
    public function showByCategory(string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);
        
        if (!$category) {
            throw $this->createNotFoundException(
                'No corresponding category to "' . $categoryName . '" in category\'s table.'
            );
        }
        
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'DESC'], 3);
        
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found for this category: ' . $category
            );
        }
        
        return $this->render('wild/category.html.twig', [
            'category' => $category,
            'programs' => $programs
        ]);
    }

    /**
     * @param string $programName Name of the program
     * @Route("/program/{programName}",
     *  name="show_program",
     *  requirements = {"programName" = "^[a-z0-9-]+$"}
     * )
     * @return Response
     */
    public function showByProgram(string $programName): Response
    {
        if (!$programName) {
            throw $this->createNotFoundException(
                "No program name was given"
            );
        }

        $programName = preg_replace(
            '/-/',
            ' ',
            ucwords(trim(strip_tags($programName)), "-")
        );

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => $programName]);
        
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with the name ' . $programName . ' was found'
            );
        }

        $programSeasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);

        return $this->render('wild/program.html.twig', [
            'program' => $program,
            'seasons' => $programSeasons
            ]);
    }

    /**
     * @param int $id Number of the season
     * @Route("/season/{id}",
     *  name="show_season",
     *  methods={"GET"},
     *  requirements = {"id" = "^[1-9]+$"}
     * )
     * @return Response
     */
    public function showBySeason(int $id): Response
    {
        if (!$id) {
            throw $this->createNotFoundException(
                "No valid id was given"
            );
        }

        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);
        
        if (!$season) {
            throw $this->createNotFoundException(
                'No season found with the id: ' . $id
            );
        }
        
        $program = $season->getProgram();
        
        $episodes = $season->getEpisodes();

        return $this->render('wild/season.html.twig', [
            'season' => $season,
            'program' => $program,
            'episodes' => $episodes,
            ]);
    }

        /**
     * @param Episode param converter of id to Episode object
     * @Route("/episode/{id}",
     *  name="show_episode",
     *  methods={"GET"}
     * )
     * @return Response
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeason();
        
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'season' => $season,
            'program' => $program,
            'episode' => $episode,
            ]);
    }


}