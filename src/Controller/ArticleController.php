<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\User;
use App\Exception\PaymentException;
use App\Form\ArticleCreationFormType;
use App\Service\ConvertCurrencyService;
use App\Service\MercureCookieGenerator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    public function showAll(){

        $allArticles = $this->getDoctrine()->getRepository(Article::class)->findAllWithAuthorAndCategorys();

        return $this->render("article/showAll.html.twig",
            [
                'allArticles' => $allArticles,
            ]
        );
    }

    public function show($id , ConvertCurrencyService $converter , MercureCookieGenerator $cookieGenerator){

        $this->denyAccessUnlessGranted('ROLE_USER');
        /** @var User $user */
        $user = $this->getUser();

        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        if(!$article){
            throw $this->createNotFoundException('Impossible de trouver l\'auteur');
        }

        $em = $this->getDoctrine()->getManager();

        try{
            $articlePrice = 1;
            if($user->getCurrency() != 'EUR'){
                $articlePrice = $converter->convert($articlePrice , 'EUR' , $user->getCurrency());
            }
            $user->decrementBalance($articlePrice);
            $em->persist($user);
        }
        catch(PaymentException $e){
            return $this->redirectToRoute('monblog_credit');
        }

        $em->flush();

        $cookie = $cookieGenerator->generatePrivate($article);
        $response = $this->render("article/show.html.twig",
            [
                'article' => $article,

            ]
        );

        $response->headers->set("set-cookie", $cookie);
        return $response;
    }


    public function create(Request $request){
        if(!$this->isGranted('ROLE_ADMIN')){
            throw $this->createNotFoundException('nope');
        }

        $article = new Article();

        $form = $this->createForm(ArticleCreationFormType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article->setCreationDate(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('monblog_article_showAll');
        }

        return $this->render("article/create.html.twig",[
            'form' => $form->createView(),
        ]);
    }

    public function update($id , Request $request)
    {
        if(!$this->isGranted('ROLE_ADMIN')){
            throw $this->createNotFoundException('nope');
        }

       $em = $this->getDoctrine()->getManager();
       $article = $em->getRepository(Article::class)->find($id);

        if(!$article){
            throw $this->createNotFoundException('Impossible de trouver l\'auteur (modification)');
        }

        $form = $this->createForm(ArticleCreationFormType::class, $article);
        $form->handleRequest(546);

        if($form->isSubmitted() && $form->isValid()){

            $article->setLastUpdateDate(new \DateTime('now'));

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('monblog_article_showAll');
        }

        return $this->render('article/update.html.twig' ,
        [
            'form' => $form->createView(),
        ]);
    }

    public function delete($id){

        if(!$this->isGranted('ROLE_ADMIN')){
            throw $this->createNotFoundException('nope');
        }

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);

        if($article){
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('monblog_article_showAll');
    }

    public function deleteAjax($id){
        if(!$this->isGranted('ROLE_ADMIN')){
            throw $this->createNotFoundException('nope');
        }

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);

        if($article){
            $em->remove($article);
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
