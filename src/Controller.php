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
use Indigo\Crud\Command;
use Indigo\Crud\Query;
use League\Route\Http\Exception\NotFoundException;
use League\Tactician\CommandBus;
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
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var array
     */
    protected $views = [
        'create' => 'create.twig',
        'read'   => 'read.twig',
        'update' => 'update.twig',
    ];

    /**
     * @param \Twig_Environment      $twig
     * @param EntityManagerInterface $em
     */
    public function __construct(\Twig_Environment $twig, EntityManagerInterface $em)
    {
        $this->twig = $twig;
        $this->em = $em;

        if (!isset($this->entityClass)) {
            throw new \LogicException('Entity class property must be defined');
        }
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function createAction(Request $request, Response $response, array $args)
    {
        $form = $this->createCreateForm();

        if ($request->attributes->get('repopulate', false)) {
            $form->populate($request->request->all());
        }

        $response->setContent($this->twig->render($this->views['create'], [
            'form' => $form,
        ]));

        return $response;
    }

    /**
     * CREATE form
     *
     * @return Form
     */
    abstract protected function createCreateForm();

    /**
     * Creates validation
     *
     * @return Validator
     */
    abstract protected function createValidator();

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function create(Request $request, Response $response, array $args)
    {
        $validator = $this->createValidator();

        $rawData = $request->request->all();

        $result = $validator->run($rawData);

        if ($result->isValid()) {
            $fields = $result->getValidated();
            $data = array_intersect_key($rawData, array_flip($fields));

            $entity = $this->createEntity($data);

            $this->em->persist($entity);
            $this->em->flush();

            return new RedirectResponse($request->getUri());
        }

        $request->attributes->set('repopulate', true);

        $response = $this->create($request, $response, $args);

        return $response;
    }

    /**
     * Creates a new entity
     *
     * @param array $data
     *
     * @return object
     */
    abstract protected function createEntity(array $data);

    /**
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
            $response->setContent($this->twig->render($this->views['read'], [
                'entity' => $entity,
            ]));

            return $response;
        }

        throw new NotFoundException;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function updateAction(Request $request, Response $response, array $args)
    {
        $form = $this->createUpdateForm();

        $entity = $this->em->getRepository($this->entityClass)->find($args['id']);

        if (!$entity) {
            throw new NotFoundException;
        }

        $data = $this->loadData($entity);

        $form->populate($data);

        $response->setContent($this->twig->render($this->views['update'], [
            'form'   => $form,
        ]));

        return $response;
    }

    /**
     * Loads data from entity
     *
     * @param object $entity
     *
     * @return array
     */
    protected function loadData($entity);

    /**
     * UPDATE form
     *
     * @return Form
     */
    abstract protected function createUpdateForm();

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function update(Request $request, Response $response, array $args)
    {
        $validator = $this->createValidator();

        $rawData = $request->request->all();

        $result = $validator->run($rawData);

        if ($result->isValid()) {
            $fields = $result->getValidated();
            $data = array_intersect_key($rawData, array_flip($fields));

            $entity = $this->em->getRepository($this->entityClass)->find($args['id']);

            $this->updateEntity($entity, $data);

            $this->em->flush();

            return new RedirectResponse(sprintf('%s', $request->getUri()));
        }

        $response = $this->update($request, $response, $args);

        return $response;
    }

    /**
     * Updates the entity
     *
     * @param object $entity
     * @param array  $data
     */
    protected function updateEntity($entity, array $data);

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

        return new RedirectResponse(sprintf('%s', $request->getBaseUrl()));
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function listAction(Request $request, Response $response, array $args)
    {
        $entities = $this->em->getRepository($this->entityClass)->findAll();

        $response->setContent($this->twig->render($this->views['list'], [
            'entities' => $entities
        ]));

        return $response;
    }
}
