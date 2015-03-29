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
use Indigo\Hydra\Hydrator;
use Indigo\Hydra\HydratorAware;
use Indigo\Hydra\HydratorAcceptor;
use League\Route\Http\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * CRUD Controller
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class Controller implements HydratorAware
{
    use HydratorAcceptor;

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
    protected $route;

    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * @var Validation
     */
    protected $validation;

    /**
     * @var array
     */
    protected $views = [
        'create' => 'create.twig',
        'read'   => 'read.twig',
        'update' => 'update.twig',
        'list'   => 'list.twig',
    ];

    /**
     * @param \Twig_Environment      $twig
     * @param EntityManagerInterface $em
     * @param Hydrator               $hydrator
     * @param FormBuilder            $formBuilder
     * @param Validation             $validation
     * @param FormTransformer        $formTransformer
     */
    public function __construct(
        \Twig_Environment $twig,
        EntityManagerInterface $em,
        Hydrator $hydrator,
        FormBuilder $formBuilder,
        Validation $validation,
        FormTransformer $formTransformer
    ) {
        $this->twig = $twig;
        $this->em = $em;
        $this->hydrator = $hydrator;
        $this->formBuilder = $formBuilder;
        $this->validation = $validation;
        $this->formTransformer = $formTransformer;

        if (!isset($this->entityClass)) {
            throw new \LogicException('The variable $entityClass must be set');
        }

        if (!class_exists($this->entityClass)) {
            throw new \LogicException(sprintf('The entity class "%s" does not exist', $this->entityClass));
        }

        if (!isset($this->route)) {
            throw new \LogicException('The variable $route must be set');
        }
    }

    /**
     * CREATE controller
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function create(Request $request, Response $response, array $args)
    {
        $form = $this->createCreateForm();

        $this->repopulate($request, $form);

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
    public function createCreateForm()
    {
        $form = new Form;
        $form->setAttribute('method', 'POST');

        $this->formBuilder->create($form, [
            'entityClass' => $this->entityClass,
        ]);

        return $form;
    }

    /**
     * CREATE handler
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function processCreate(Request $request, Response $response, array $args)
    {
        $validator = $this->createCreateValidator();

        $rawData = $request->request->all();

        $result = $validator->run($rawData);

        if ($result->isValid()) {
            $fields = $result->getValidated();
            $data = array_intersect_key($rawData, array_flip($fields));

            $formTransformer = $this->getFormTransformer();
            $data = $formTransformer->transformToInternal($data);

            $entity = new $this->entityClass;

            // UGLY WORKAROUND BEGINS
            $data = array_merge($this->hydrator->extract($entity), $data);
            // UGLY WORKAROUND ENDS

            $this->hydrator->hydrate($entity, $data);

            $this->em->persist($entity);
            $this->em->flush();

            return new RedirectResponse(sprintf('%s%s', $request->attributes->get('stack.url_map.prefix', ''), $this->route));
        }

        $request->attributes->set('repopulate', true);

        $response = $this->create($request, $response, $args);

        return $response;
    }

    /**
     * CREATE validation
     *
     * @return Validator
     */
    public function createCreateValidator()
    {
        $validator = new Validator;

        $this->validation->buildValidation($validator, $entity, [
            'entityClass' => $this->entityClass,
        ]);

        return $validator;
    }

    /**
     * READ controller
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
            $response->setContent($this->twig->render($this->views['read'], [
                'entity' => $entity,
            ]));

            return $response;
        }

        throw new NotFoundException;
    }

    /**
     * UPDATE controller
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function update(Request $request, Response $response, array $args)
    {
        $form = $this->createUpdateForm();

        $entity = $this->em->getRepository($this->entityClass)->find($args['id']);

        $data = $this->hydrator->extract($entity);

        $formTransformer = $this->getFormTransformer();
        $data = $formTransformer->transformToDisplay($data);

        $form->populate($data);

        $response->setContent($this->twig->render($this->views['update'], [
            'form'   => $form,
            'entity' => $entity,
        ]));

        return $response;
    }

    /**
     * UPDATE form
     *
     * @return Form
     */
    public function createUpdateForm()
    {
        $form = new Form;
        $form->setAttribute('method', 'PUT');

        $this->formBuilder->create($form, [
            'entityClass' => $this->entityClass,
        ]);

        return $form;
    }

    /**
     * UPDATE handler
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function processUpdate(Request $request, Response $response, array $args)
    {
        $validator = $this->createUpdateValidator();

        $rawData = $request->request->all();

        $result = $validator->run($rawData);

        $entity = $this->em->getRepository($this->entityClass)->find($args['id']);

        if ($result->isValid()) {
            $fields = $result->getValidated();
            $data = array_intersect_key($rawData, array_flip($fields));

            $formTransformer = $this->getFormTransformer();
            $data = $formTransformer->transformToInternal($data);

            // UGLY WORKAROUND BEGINS
            $data = array_merge($this->getHydrator()->extract($entity), $data);
            // UGLY WORKAROUND ENDS

            $this->getHydrator()->hydrate($entity, $data);

            $this->em->flush();

            return new RedirectResponse(sprintf('%s%s', $request->attributes->get('stack.url_map.prefix', ''), $this->route));
        }

        $response = $this->update($request, $response, $args);

        return $response;
    }

    /**
     * UPDATE validation
     *
     * @return Validator
     */
    public function createUpdateValidator()
    {
        $validator = new Validator;

        $this->validation->buildValidation($validator, $entity, [
            'entityClass' => $this->entityClass,
        ]);

        return $validator;
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

        return new RedirectResponse(sprintf('%s%s', $request->attributes->get('stack.url_map.prefix', ''), $this->route));
    }

    /**
     * List controller
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function index(Request $request, Response $response, array $args)
    {
        $response->setContent($this->twig->render($this->views['list']));

        return $response;
    }

    /**
     * Tries to repopulate a form after failure
     *
     * @param Request $request
     * @param Form    $form
     */
    protected function repopulate(Request $request, Form $form)
    {
        if ($request->attributes->get('repopulate', false)) {
            $form->populate($request->request->all());
        }
    }
}
