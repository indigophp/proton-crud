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
use Fuel\Fieldset\Builder\Basic;
use Fuel\Fieldset\Form;
use Fuel\Validation\Validator;
use Fuel\Validation\RuleProvider\FromArray;
use GeneratedHydrator\Configuration;
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
        $form = $this->createCreateForm();

        $this->repopulate($request, $form);

        $response->setContent($this->twig->render('create.twig', [
            'form' => $form,
        ]));

        return $response;
    }

    /**
     * Creates a new form for creation
     *
     * @return Form
     */
    protected function createCreateForm()
    {
        $form = $this->createForm();

        return $form;
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

            $this->hydrate($entity, $data);

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
        $form = $this->createUpdateForm();

        $entity = $this->em->getRepository($this->entityClass)->find($args['id']);

        $form->populate($this->extract($entity));

        $response->setContent($this->twig->render('update.twig', [
            'form'   => $form,
            'entity' => $entity,
        ]));

        return $response;
    }

    /**
     * Creates a new form for update
     *
     * @return Form
     */
    protected function createUpdateForm()
    {
        $form = $this->createForm();

        return $form;
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

            $this->hydrate($entity, $data);

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
     * Creates a new form
     *
     * @return Form
     */
    protected function createForm()
    {
        $form = new Form;
        $builder = new Basic;

        $metadata = $this->em->getClassMetadata($this->entityClass);
        $fields = $metadata->fieldMappings;

        foreach ($fields as $name => $mappings) {
            if (isset($mappings['options']['form'])) {
                $data = array_merge([
                    'name'  => $name,
                    'label' => isset($mappings['options']['label']) ? $mappings['options']['label'] : null,
                ], $mappings['options']['form']);

                $form[$name] = $builder->generate([$data])[0];
            }
        }

        return $form;
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
     * Hydrates data into an entity
     *
     * @param object $entity
     * @param array  $data
     */
    protected function hydrate($entity, array $data)
    {
        $hydrator = $this->getHydratorFor($entity);

        $hydrator->hydrate($data, $entity);
    }

    /**
     * Extracts data from an entity
     *
     * @param object $entity
     *
     * @return array
     */
    protected function extract($entity)
    {
        $hydrator = $this->getHydratorFor($entity);

        return $hydrator->extract($entity);
    }

    /**
     * Returns a GeneratedHydrator for the object
     *
     * @param object $object
     *
     * @return GeneratedHydrator
     */
    private function getHydratorFor($object)
    {
        $class         = get_class($object);
        $config        = new Configuration($class);
        $hydratorClass = $config->createFactory()->getHydratorClass();

        return new $hydratorClass;
    }
}
