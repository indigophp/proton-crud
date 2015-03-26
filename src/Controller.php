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
use Fuel\Validation\RuleProvider\FromArray;
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
    protected $hydratorClass = 'Proton\Crud\Hydrator\GeneratedHydrator';

    /**
     * @var Hydrator
     */
    protected $hydrator;

    /**
     * @var string
     */
    protected $formBuilderClass = 'Proton\Crud\FormBuilder\EntityMetadata';

    /**
     * @var FormBuilder
     */
    protected $formBuilder;

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

        if (!class_exists($this->entityClass)) {
            throw new \LogicException(sprintf('The entity class "%s" does not exist', $this->entityClass));
        }

        if (!is_subclass_of($this->hydratorClass, 'Proton\Crud\Hydrator')) {
            throw new \LogicException('The hydrator class must implement Proton\Crud\Hydrator');
        }

        if (!is_subclass_of($this->formBuilderClass, 'Proton\Crud\FormBuilder')) {
            throw new \LogicException('The form builder class must implement Proton\Crud\FormBuilder');
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
        $this->getFormBuilder()->create($form);

        $this->repopulate($request, $form);

        $response->setContent($this->twig->render('create.twig', [
            'form' => $form,
        ]));

        return $response;
    }

    /**
     * Creates a new validator for creation
     *
     * @return Validator
     */
    protected function createCreateValidator()
    {
        $validator = $this->createValidator();

        return $validator;
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
        $val = $this->createCreateValidator();

        $rawData = $request->request->all();

        $result = $val->run($rawData);

        if ($result->isValid()) {
            $fields = $result->getValidated();
            $data = array_intersect_key($rawData, array_flip($fields));

            $entity = new $this->entityClass;

            $this->getHydrator()->hydrate($entity, $data);

            $this->em->persist($entity);
            $this->em->flush();

            return new RedirectResponse('/');
        }

        $request->attributes->set('repopulate', true);

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
        $this->getFormBuilder()->update($form);

        $entity = $this->em->getRepository($this->entityClass)->find($args['id']);

        $form->populate($this->getHydrator()->extract($entity));

        $response->setContent($this->twig->render('update.twig', [
            'form'   => $form,
            'entity' => $entity,
        ]));

        return $response;
    }

    /**
     * Creates a new validator for update
     *
     * @return Validator
     */
    protected function createUpdateValidator()
    {
        $validator = $this->createValidator();

        return $validator;
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
        $val = $this->createUpdateValidator();

        $result = $val->run($request->request->all());

        $entity = $this->em->getRepository($this->entityClass)->find($args['id']);

        if ($result->isValid()) {
            $data = $result->getValidated();

            $this->getHydrator()->hydrate($entity, $data);

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
        $response->setContent($this->twig->render('index.twig'));

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

    /**
     * Creates a new validator
     *
     * @return Validator
     */
    protected function createValidator()
    {
        $validator = new Validator;
        $ruleProvider = new FromArray(true);

        $metadata = $this->em->getClassMetadata($this->entityClass);
        $fields = $metadata->fieldMappings;
        $data = [];

        foreach ($fields as $name => $mappings) {
            if (isset($mappings['options']['validation'])) {
                $data[$name] = [
                    'label' => isset($mappings['options']['label']) ? $mappings['options']['label'] : null,
                    'rules' => $mappings['options']['validation'],
                ];
            }
        }

        $ruleProvider->setData($data);

        return $ruleProvider->populateValidator($validator);
    }

    /**
     * Returns the Hydrator object and optionally instantiates it
     *
     * @return Hydrator
     */
    public function getHydrator()
    {
        if (!isset($this->hydrator)) {
            $this->hydrator = new $this->hydratorClass;
        }

        return $this->hydrator;
    }

    /**
     * Sets a custom Hydrator
     *
     * Useful when the hydrator has external dependencies
     *
     * @param Hydrator $hydrator
     */
    public function setHydrator(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * Returns the FormBuilder object and optionally instantiates it
     *
     * @return FormBuilder
     */
    public function getFormBuilder()
    {
        if (!isset($this->formBuilder)) {
            // TODO: find a better way
            if (is_subclass_of($this->formBuilderClass, 'Proton\Crud\FormBuilder\EntityMetadata')) {
                $this->formBuilder = new $this->formBuilderClass($this->em, $this->entityClass);
            } else {
                $this->formBuilder = new $this->formBuilderClass;
            }
        }

        return $this->formBuilder;
    }

    /**
     * Sets a custom FormBuilder
     *
     * Useful when the form builder has external dependencies
     *
     * @param FormBuilder $formBuilder
     */
    public function setFormBuilder(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }
}
