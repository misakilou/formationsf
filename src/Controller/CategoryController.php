<?php


namespace App\Controller;


use App\Entity\Author;
use App\Entity\Categorys;
use App\Form\CategorysType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    public function showAll(Request $request){

        $em = $this->getDoctrine()->getManager();
        $allCategorys = $em->getRepository(Categorys::class)->findAll();
        $category = new Categorys();
        $form = $this->createForm(CategorysType::class , $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('monblog_category_showAll');
        }

        return $this->render("category/showAll.html.twig",
            [
                'allCategorys' => $allCategorys,
                'form' => $form->createView(),

            ]
        );
    }


    public function update($id , Request $request)
    {
       $em = $this->getDoctrine()->getManager();
       $category = $em->getRepository(Categorys::class)->find($id);

        if(!$category){
            throw $this->createNotFoundException('Impossible de trouver l\'auteur (modification)');
        }

        $form = $this->createForm(CategorysType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('monblog_category_showAll');
        }

        return $this->render('category/update.html.twig' ,
        [
            'form' => $form->createView(),
        ]);
    }

    public function deleteAjax($id){
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Categorys::class)->find($id);

        if($category){
            $em->remove($category);
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
