<?php

namespace App\Controller;

use App\Repository\BookReadRepository;
use App\Entity\BookRead;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;

class HomeController extends AbstractController
{
    private BookReadRepository $readBookRepository;

    // Inject the repository via the constructor
    public function __construct(BookReadRepository $bookReadRepository)
    {
        $this->bookReadRepository = $bookReadRepository;
    }

    #[Route('/', name: 'app.home')]
    public function index(Request $request, ManagerRegistry $registry): Response
    {
        $user = $this->getUser();
        if($user){
            $userId = $user->getId();
            $book = new BookRead();
            $categoryRepo = new CategoryRepository($registry);
            // $form = $this->createForm(BookFormType::class, $book);
            // $form->handleRequest($request);
    
            $booksRead  = $this->bookReadRepository->findByUserId($userId, false);
            $booksReaded  = $this->bookReadRepository->findByUserId($userId, true);
    
    
            $categories = $categoryRepo->getCategories();
            
            $count = $categoryRepo->countCategoriesByUser($user->getId());
            $counter = array();
            foreach ($categories as $key => $value) {
                foreach($count as $countkey => $countvalue) {
                    if($value['id'] == $countvalue['id']) {
                        $counter[$value['id']] = $countvalue['NUM'];
                        break;
                    } else {
                        $counter[$value['id']] =  0;
                    }
                }
            }
        }
        // Render the 'hello.html.twig' template
        return $this->render('pages/home.html.twig', [
            'booksRead' => isset($booksRead) ? $booksRead : "",
            'name'      => 'Accueil', // Pass data to the view
            'email' => $user ? $user->getEmail() : "",
            'form' => isset($form) ? $form : "",
            'categories' => isset($categories) ? $categories : "",
            'counter' => isset($counter) ? $counter : "",
            'booksReaded' => isset($booksReaded) ? $booksReaded : ""
        ]);
    }


    // #[Route('/register', name: 'auth.register')]
    // public function register(): Response
    // {   
    //     return $this->render('registration/register.html.twig', [
    //         'name' => 'Thibaud', // Pass data to the view
    //     ]);
    // }
}
