<?php

/*
 * This file is part of the Proton CRUD package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Proton\Crud;

use Doctrine\ORM\EntityManagerInterface;
use Fuel\Fieldset\Form;
use Fuel\Validation\Validator;
use League\Route\Http\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * CRUD Controller
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class Controller
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $formClass = 'Proton\Crud\Form';

    /**
     * @var string
     */
    protected $validationClass = 'Proton\Crud\Validation';

    /**
     * @var string
     */
    protected $hydratorClass = 'Proton\Crud\Hydrator\GeneratedHydrator';

    /**
     * @param \Twig_Environment      $twig
     * @param EntityManagerInterface $em
     */
    public function __construct(\Twig_Environment $twig, EntityManagerInterface $em)
    {
        $this->twig = $twig;
        $this->em = $em;

        if (!isset($this->entityClass)) {
            throw new \LogicException('The variable $entityClass must be set');
        }
    }

    /**
     * Create controller
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function create(Request $request, Response $response, array $args)
    {
        $form = new Form;

        $formBuilder = new $this->formClass;

        $formBuilder->build($form);

        $response->setContent($this->twig->render('create.twig', [
            'form' => $form,
        ]));

        return $response;
    }

    /**
     * Create POST handler
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function processCreate(Request $request, Response $response, array $args)
    {
        $val = new Validator;

        $validation = new $this->validationClass;

        $validation->populateValidator($val);

        $result = $val->run($request->request->all());

        if ($result->isValid()) {
            $data = $result->getValidated();

            $entity = new $this->entityClass;
            $hydrator = new $this->hydratorClass;

            $hydrator->hydrate($entity, $data);

            $this->em->persist($entity);
            $this->em->flush();

            return new RedirectResponse('/');
        }

        $response = $this->create($request, $response, $args);

        return $response;
    }

    /**
     * Read controller
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function read(Request $request, Response $response, array $args)
    {
        $entity = $this->em->getRepository($this->entityClass)->find($args['id']);

        if ($entity) {
            $response->setContent($this->twig->render('read.twig', [
                'entity' => $entity,
            ]));

            return $response;
        }

        throw new NotFoundException;
    }

    /**
     * Update controller
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function update(Request $request, Response $response, array $args)
    {
        $form = new Form;

        $formBuilder = new $this->formClass;

        $formBuilder->build($form);

        $entity = $this->em->getRepository($this->entityClass)->find($args['id']);

        $hydrator = new $this->hydratorClass;

        $form->populate($hydrator->extract($entity));

        $response->setContent($this->twig->render('update.twig', [
            'form'   => $form,
            'entity' => $entity,
        ]));

        return $response;
    }

    /**
     * Update POST handler
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function processUpdate(Request $request, Response $response, array $args)
    {
        $val = new Validator;

        $validation = new $this->validationClass;

        $validation->populateValidator($val);

        $result = $val->run($request->request->all());

        $entity = $this->em->getRepository($this->entityClass)->find($args['id']);

        if ($result->isValid()) {
            $data = $result->getValidated();

            $hydrator = new $this->hydratorClass;

            $hydrator->hydrate($entity, $data);

            $this->em->flush();

            return new RedirectResponse('/');
        }

        $response = $this->update($request, $response, $args);

        return $response;
    }

    /**
     * Delete controller
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function delete(Request $request, Response $response, array $args)
    {
        $entity = $this->em->getRepository($this->entityClass)->find($args['id']);

        if ($entity) {
            $this->em->remove($entity);
            $this->em->flush();
        }

        return new RedirectResponse('/');
    }
}
