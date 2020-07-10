<?php


namespace App\Controller;


use App\Entity\Author;
use App\Form\AuthorCreationFormType;
use App\Repository\AuthorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends AbstractController
{
    public function showAll(){

        $allAuthors = $this->getDoctrine()->getRepository(Author::class)->findBy([],
            ['firstName' => 'asc',
            'LastName' => 'asc']);

        return $this->render("author/showAll.html.twig",
            [
                'allAuthors' => $allAuthors,

            ]
        );
    }

    public function show($id){

        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);

        if(!$author){
            throw $this->createNotFoundException('Impossible de trouver l\'auteur');
        }
        return $this->render("author/show.html.twig",
            [
                'author' => $author,

            ]
        );
    }

    public function create(Request $request){

        if(!$this->isGranted('ROLE_ADMIN')){
            throw $this->createNotFoundException('nope');
        }

        $author = new Author();

        $form = $this->createForm(AuthorCreationFormType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('monblog_author_showAll');
        }

        return $this->render("author/create.html.twig",[
            'form' => $form->createView(),
        ]);
    }

    public function update($id , Request $request)
    {
        if(!$this->isGranted('ROLE_ADMIN')){
            throw $this->createNotFoundException('nope');
        }
       $em = $this->getDoctrine()->getManager();
       $author = $em->getRepository(Author::class)->find($id);

        if(!$author){
            throw $this->createNotFoundException('Impossible de trouver l\'auteur (modification)');
        }

        $form = $this->createForm(AuthorCreationFormType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('monblog_author_showAll');
        }

        return $this->render('author/update.html.twig' ,
        [
            'form' => $form->createView(),
        ]);
    }

    public function deleteAjax($id){

        if(!$this->isGranted('ROLE_ADMIN')){
            throw $this->createNotFoundException('nope');
        }

        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository(Author::class)->find($id);

        if($author){
            $em->remove($author);
            $em->flush();
        }

        return $this->json(
            [
                'success' => true,
                'message' => 'OK !',
            ]
        );
    }

}
