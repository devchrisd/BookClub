<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Data\MemberData;
use AppBundle\Form\MemberType;
use AppBundle\Repository\MemberRepository;
use AppBundle\Model\Address;
use AppBundle\Model\BodyType;
use AppBundle\Model\Email;
use AppBundle\Model\Ethnicity;
use AppBundle\Model\Height;
use AppBundle\Model\Category;
use AppBundle\Model\Member;
use AppBundle\Model\Weight;
use AppBundle\Entity\Members;

final class MemberController extends Controller
{
    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @return Response
     */
    public function manageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $this->memberRepository = $em->getRepository('AppBundle:Members');

        return $this->render(
            'AppBundle:member:members.html.twig',
            ['members' => $this->memberRepository->findAll()]
        );
    }

    /**
     * @param Request $request
     * @param Member $member
     *
     * @return Response
     */
    public function profileAction(Request $request, $username)
    {
        $em = $this->getDoctrine()->getManager();
        $this->memberRepository = $em->getRepository('AppBundle:Members');
        $member = $this->memberRepository->findOneByUsername($username);

        return $this->render(
            'AppBundle:member:profile.html.twig',
            ['member' => $member]
        );
    }

    /**
     * @param Member $member
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $this->memberRepository = $em->getRepository('AppBundle:Members');

        $username = $request->get('username');
        $member = $this->memberRepository->findOneByUsername($username);

        if (!$member)
        {
            $this->addFlash(
                'warning',
                sprintf('Unable to find member %s', $member->getUsername())
            );
        }

        $em->remove($member);

        try {
            $em->flush();
            $this->addFlash(
                'warning',
                sprintf('Member %s has been removed', $member->getUsername()));
        } catch (Exception $e) {
            $this->addFlash(
                'warning',
                sprintf('Unable to remove member %s', $member->getUsername())
            );
        }

        return $this->redirectToRoute('member');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new MemberType());

        if ($request->isMethod('post')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $members = $this->convertDataToMembers($form->getData());

                $em = $this->getDoctrine()->getManager();
                $em->persist($members);

                try {
                    $em->flush();

                    $this->addFlash(
                        'notice',
                        sprintf('Member "%s" was successfully added.', $members->getUsername())
                    );

                } catch (Exception $e) {

                    $logger = $this->get('logger');
                    $logger->info(__LINE__ . print_r($members, 1));

                    $this->addFlash(
                        'warning',
                        sprintf(sprintf('Member "%s" could not be added.', $members->getUsername()))
                    );
                }

                return $this->redirectToRoute('member');
            }
        }

        return $this->render(
            'AppBundle:member:members.add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param MemberData $data
     *
     * @return Member
     */
    private function convertDataToMembers(MemberData $data)
    {
        // $member = new Member(
        //     $data->username,
        //     $data->password,
        //     new Address('Canada', 'Ontario', $data->city, $data->postalCode),
        //     $data->dateOfBirth,
        //     Category::all()[$data->category],
        //     new Height($data->height),
        //     new Weight($data->weight),
        //     BodyType::all()[$data->bodyType],
        //     Ethnicity::all()[$data->ethnicity],
        //     new Email($data->email)
        // );
        // $members
        //     ->setUsername($member->getUsername())
        //     ->setPassword($member->getPassword())
        //     ->setCountry($member->getAddress()->getCountry())
        //     ->setProvince($member->getAddress()->getProvince())
        //     ->setCity($member->getAddress()->getCity())
        //     ->setPostalCode($member->getAddress()->getPostalCode())
        //     ->setDateOfBirth($member->getDateOfBirth()->format('Y-m-d'))
        //     ->setCategory($member->getCategory())
        //     ->setBodyType($member->getBodyType())
        //     ->setEthnicity($member->getEthnicity())
        //     ->setHeight((string)$member->getHeight())
        //     ->setWeight((string)$member->getWeight())
        //     ->setEmail((string)$member->getEmail())
        //     ;
        $members = new Members();

        $members
            ->setUsername($data->username)
            ->setPassword($data->password)
            ->setCountry('Canada')
            ->setProvince('Ontario')
            ->setCity($data->city)
            ->setPostalCode($data->postalCode)
            ->setDateOfBirth($data->dateOfBirth->format('Y-m-d'))
            ->setCategory(Category::all()[$data->category])
            ->setBodyType(BodyType::all()[$data->bodyType])
            ->setEthnicity(Ethnicity::all()[$data->ethnicity])
            ->setHeight($data->height)
            ->setWeight($data->weight)
            ->setEmail($data->email)
            ;

        return $members;
    }
}
