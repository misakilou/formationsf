<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
{

    public function creditAccount(Request $request){

        $this->denyAccessUnlessGranted('ROLE_USER');

        $amount = $request->request->get('amount');
        if($amount !== null && $amount > 0)
        {
            /** @var User $user */
            $user = $this->getUser();
            $amount = $user->incrementBalance($amount);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('monblog_home');

        }



        return $this->render('payment/credit.html.twig');

    }

}