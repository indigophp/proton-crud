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
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @var Configuration
     */
    protected $config;


    /**
     * @param \Twig_Environment $twig
     * @param CommandBus        $commandBus
     * @param Configuration     $config
     */
    public function __construct(
        \Twig_Environment $twig,
        CommandBus $commandBus,
        Configuration $config
    ) {
        $this->twig = $twig;
        $this->commandBus = $commandBus;
        $this->config = $config;
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

        $response->setContent($this->twig->render($this->config->getViewFor('create'), [
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

            $command = new Command\CreateEntity($this->config,  $data);

            $this->commandBus->handle($command);

            return new RedirectResponse($request->getUri());
        }

        $request->attributes->set('repopulate', true);

        $response = $this->create($request, $response, $args);

        return $response;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function read(Request $request, Response $response, array $args)
    {
        $query = new Query\FindEntity($this->config, $args['id']);

        $entity = $this->commandBus->handle($query);

        if ($entity) {
            $response->setContent($this->twig->render($this->config->getViewFor('read'), [
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

        $query = new Query\LoadEntity($this->config, $args['id']);

        $data = $this->commandBus->handle($query);

        $form->populate($data);

        $response->setContent($this->twig->render($this->config->getViewFor('update'), [
            'form'   => $form,
        ]));

        return $response;
    }

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

            $query = new Query\FindEntity($this->config, $args['id']);

            $entity = $this->commandBus->handle($query);

            $command = new Command\UpdateEntity($this->config, $entity, $data);

            $this->commandBus->handle($command);

            return new RedirectResponse(sprintf('%s%s', $request->getBaseUrl(), $this->config->getRoute()));
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
        $query = new Query\FindEntity($this->config, $args['id']);

        $entity = $this->commandBus->handle($query);

        if ($entity) {
            $command = new Command\DeleteEntity($this->config, $entity);

            $this->commandBus->handle($command);
        }

        return new RedirectResponse(sprintf('%s%s', $request->getBaseUrl(), $this->config->getRoute()));
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
        $query = new Query\FindAllEntities($this->config);

        $entities = $this->commandBus->handle($query);

        $response->setContent($this->twig->render($this->config->getViewFor('list'), [
            'entities' => $entities
        ]));

        return $response;
    }
}
