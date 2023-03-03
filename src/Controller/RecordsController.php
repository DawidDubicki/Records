<?php

namespace App\Controller;

use App\Entity\Nosnik;
use App\Entity\Utwory;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
class RecordsController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homePage()
    {
        return $this->render('homepage.html.twig');
    }
    /**
     * @Route("/api/nosniki")
     */
    public function nosniki(Request $request, ManagerRegistry $doctrine)
    {
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER=> function ($object, $format, $context) {
                return $object->getUtwory();
            },
            ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer([$normalizer], $encoders);
        $entityManager = $doctrine->getManager();
        if ($request->getMethod() == 'GET') {
            $data = $entityManager->getRepository(Nosnik::class)->findBy([], ['id' => 'ASC']);
            $serializedData = $serializer->serialize($data, 'json');
            return new Response($serializedData);
        }
        else {
            $id = $request->request->get('id');
            if ($id) {
                $data = $entityManager->getRepository(Nosnik::class)->find($id);
                $serializedData = $serializer->serialize($data, 'json');
                return new Response($serializedData);
            }
            else {
                return New Response('', Response::HTTP_BAD_REQUEST);
            }
        }
    }
    /**
     * @Route("/api/nosniki/remove")
     */
    public function removeNosniki(Request $request, ManagerRegistry $doctrine)
    {
        if ($request->getMethod() == 'POST')
        {
            $id = $request->request->get('id');
            if ($id) {
                $entityManager = $doctrine->getManager();
                $nosnik = $doctrine->getRepository(Nosnik::class)->find($id);
                if ($nosnik) {
                    $utwory = $doctrine->getRepository(Utwory::class)->findBy(['nosnik' => $nosnik]);
                    foreach ($utwory as $utwor) {
                        $entityManager->remove($utwor);
                        $entityManager->flush();
                    }
                    $entityManager->remove($nosnik);
                    $entityManager->flush();
                    return new Response('', Response::HTTP_OK);
                }
                return new Response('', Response::HTTP_BAD_REQUEST);
            }
            return New Response('', Response::HTTP_BAD_REQUEST);
        }
        else {
            return New Response('', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    /**
     * @Route("/api/nosniki/create")
     */
    public function createNosniki(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator)
    {
        if ($request->getMethod() == 'POST') {
            $title = $request->request->get('title');
            $artist = $request->request->get('artist');
            $date = $request->request->get('releaseDate');
            $carrier = $request->request->get('carrier');
            $entityManager = $doctrine->getManager();
            $nosnik = new Nosnik();
            $nosnik->setTytul($title);
            $nosnik->setArtysta($artist);
            $nosnik->setRokWydania($date);
            $nosnik->setNosnik($carrier);
            $error = $validator->validate($nosnik);
            if (count($error) > 0) {
                return new Response('Wykryto problem');
            }
            else {
                $entityManager->persist($nosnik);
                $entityManager->flush();
            }
            return new Response('', Response::HTTP_CREATED);
        }
        else {
            return New Response('', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    /**
     * @Route("/api/nosniki/update")
     */
    public function updateNosniki(Request $request, ManagerRegistry $doctrine) {
        if ($request->getMethod() == 'POST') {
            $entityManager = $doctrine->getManager();
            $id = $request->request->get('id');
            $title = $request->request->get('title');
            $artist = $request->request->get('artist');
            $date = $request->request->get('releaseDate');
            $carrier = $request->request->get('carrier');
            $nosnik = $doctrine->getRepository(Nosnik::class)->find($id);
            if ($nosnik) {
                $nosnik->setTytul($title);
                $nosnik->setArtysta($artist);
                $nosnik->setRokWydania($date);
                $nosnik->setNosnik($carrier);
                $entityManager->persist($nosnik);
                $entityManager->flush();
                return new Response('', Response::HTTP_OK);
            }
            else {
                return new Response('', Response::HTTP_BAD_REQUEST);
            }
        }
        else {
            Return new Response('', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    /**
     * @Route("/api/utwory/create")
     */
    public function createUtwory(Request $request, ManagerRegistry $doctrine) {
        if ($request->getMethod() == 'POST') {
            $id = $request->request->get('id');
            $title = $request->request->get('title');
            $nosnik = $doctrine->getRepository(Nosnik::class)->find($id);
            if (!$nosnik or !$title) {
                return new Response('', Response::HTTP_BAD_REQUEST);
            }
            $artist = $nosnik->getArtysta();
            $utwor = new Utwory();
            $utwor->setTytulUtworu($title);
            $utwor->setNosnik($nosnik);
            $utwor->setArtysta($artist);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($utwor);
            $entityManager->flush();
            return new Response('', Response::HTTP_OK);
        }
        else {
            return new Response('', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    /**
     * @Route("/api/utwory")
     */
    public function utwory(Request $request, ManagerRegistry $doctrine){
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER=> function ($object, $format, $context) {
                return $object->getNosnik();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer([$normalizer], $encoders);
        if ($request->getMethod() == 'GET') {
            $utwory = $doctrine->getRepository(Utwory::class)->findAll();
            if ($utwory) {
                $serializedData = $serializer->serialize($utwory, 'json');
                return new Response($serializedData);
            }
            else {
                return new Response('', Response::HTTP_BAD_REQUEST);
            }
        }
        if ($request->getMethod() == 'POST') {
            $id = $request->request->get('id');
            $utwor = $doctrine->getRepository(Utwory::class)->find($id);
            if (!$utwor) {
                return new Response('', Response::HTTP_BAD_REQUEST);
            }
            $serializedData = $serializer->serialize($utwor, 'json');
            return new Response($serializedData);
        }
    }

    /**
     * @Route("/api/utwory/remove")
     */
    public function removeUtwory(Request $request, ManagerRegistry $doctrine)
    {
        if ($request->getMethod() == 'POST')
        {
            $id = $request->request->get('id');
            if ($id) {
                $entityManager = $doctrine->getManager();
                $utwor = $doctrine->getRepository(Utwory::class)->find($id);
                if ($utwor) {
                    $entityManager->remove($utwor);
                    $entityManager->flush();
                    return new Response('', Response::HTTP_OK);
                }
                return new Response('', Response::HTTP_BAD_REQUEST);
            }
            return New Response('', Response::HTTP_BAD_REQUEST);
        }
        else {
            return New Response('', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }
    /**
     * @Route("/api/utwory/update")
     */
    public function updateUtwory(Request $request, ManagerRegistry $doctrine) {
        if ($request->getMethod() == 'POST') {
            $entityManager = $doctrine->getManager();
            $id = $request->request->get('id');
            $title = $request->request->get('title');
            $carrier_id = $request->request->get('carrier_id');
            $nosnik = $doctrine->getRepository(Nosnik::class)->find($carrier_id);
            $utwor = $doctrine->getRepository(Utwory::class)->find($id);
            if ($utwor and $nosnik) {
                $utwor->setTytulUtworu($title);
                $utwor->setNosnik($nosnik);
                $entityManager->persist($utwor);
                $entityManager->flush();
                return new Response('', Response::HTTP_OK);
            }
            else {
                return new Response('', Response::HTTP_BAD_REQUEST);
            }
        }
        else {
            Return new Response('', Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }
}