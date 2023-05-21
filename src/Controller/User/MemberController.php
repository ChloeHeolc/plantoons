<?php

namespace App\Controller\User;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MemberController extends AbstractController
{
    /**
     * @Route("user/member", name="user_app_member")
     */
    public function index(): Response
    {
        return $this->render('user/member/index.html.twig', [
            'controller_name' => 'MemberController',
        ]);
    }

    //Members List
    /**
     * @Route("user/members", name="user_members_list")
     */
    public function listMembers(MemberRepository $memberRepository): Response {
        $members = $memberRepository->findAll();

        return $this->render("user/member/members_list.html.twig", ['members' => $members]);
    }

    //View details member
    /**
     * @Route("user/member/{id}", name="user_member_show")
     */
    public function showMember($id, MemberRepository $memberRepository) {
        $member = $memberRepository->find($id);

        return $this->render("user/member/member_show.html.twig", ['member' => $member]);
    }

    //Edit detail member
    /**
     * @Route("user/update/member/{id}", name="user_update_member")
     */
    public function updateMember($id, MemberRepository $memberRepository, EntityManagerInterface $entityManagerInterface, Request $request) {
        $member = $memberRepository->find($id);

        $memberForm = $this->createForm(MemberType::class, $member);
        $memberForm->handleRequest($request);

        if ($memberForm->isSubmitted() && $memberForm->isValid()) {

            $entityManagerInterface->persist($member);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("user_members_list");
        }
        return $this->render("user/member/member_form.html.twig", ['memberForm' => $memberForm->createView()]);
    }

    //Create new member
    /**
     * @Route("user/create/member", name="user_create_member")
     */
    public function createMember(EntityManagerInterface $entityManagerInterface, Request $request) {

        $member = new Member();

        $memberForm = $this->createForm(MemberType::class, $member);

        $memberForm->handleRequest($request);

        if ($memberForm->isSubmitted() && $memberForm->isValid()) {
            $entityManagerInterface->persist($member);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("user_members_list");
        }
        return $this->render("user/member/member_form.html.twig", ['memberForm' => $memberForm->createView()]);
    }

    //Delete member
    /**
     * @Route("user/delete/member/{id}", name="user_delete_member")
     */
    public function deleteMember($id, MemberRepository $memberRepository, EntityManagerInterface $entityManagerInterface) {
        $member = $memberRepository->find($id);

        $entityManagerInterface->remove($member);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("user/member/members_list");
    }
}
