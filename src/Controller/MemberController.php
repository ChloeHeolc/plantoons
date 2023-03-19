<?php

namespace App\Controller;

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
     * @Route("/member", name="app_member")
     */
    public function index(): Response
    {
        return $this->render('member/index.html.twig', [
            'controller_name' => 'MemberController',
        ]);
    }

    //Members List
    /**
     * @Route("members", name="members_list")
     */
    public function listMembers(MemberRepository $memberRepository): Response {
        $members = $memberRepository->findAll();

        return $this->render("member/members_list.html.twig", ['members' => $members]);
    }

    //View details member
    /**
     * @Route("member/{id}", name="member_show")
     */
    public function showMember($id, MemberRepository $memberRepository) {
        $member = $memberRepository->find($id);

        return $this->render("member/member_show.html.twig", ['member' => $member]);
    }

    //Edit detail member
    /**
     * @Route("update/member/{id}", name="update_member")
     */
    public function updateMember($id, MemberRepository $memberRepository, EntityManagerInterface $entityManagerInterface, Request $request) {
        $member = $memberRepository->find($id);

        $memberForm = $this->createForm(MemberType::class, $member);
        $memberForm->handleRequest($request);

        if ($memberForm->isSubmitted() && $memberForm->isValid()) {

            $entityManagerInterface->persist($member);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("members_list");
        }
        return $this->render("member/member_form.html.twig", ['memberForm' => $memberForm->createView()]);
    }

    //Create new member
    /**
     * @Route("create/member", name="create_member")
     */
    public function createMember(EntityManagerInterface $entityManagerInterface, Request $request) {

        $member = new Member();

        $memberForm = $this->createForm(MemberType::class, $member);

        $memberForm->handleRequest($request);

        if ($memberForm->isSubmitted() && $memberForm->isValid()) {
            $entityManagerInterface->persist($member);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("members_list");
        }
        return $this->render("member/member_form.html.twig", ['memberForm' => $memberForm->createView()]);
    }

    //Delete member
    /**
     * @Route("delete/member/{id}", name="delete_member")
     */
    public function deleteMember($id, MemberRepository $memberRepository, EntityManagerInterface $entityManagerInterface) {
        $member = $memberRepository->find($id);

        $entityManagerInterface->remove($member);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("member/members_list");
    }
}
