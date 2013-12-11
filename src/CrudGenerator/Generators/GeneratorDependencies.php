<?php

namespace CrudGenerator\Generators;

use CrudGenerator\History\HistoryManager;
use CrudGenerator\Command\CreateCommand;
use CrudGenerator\DataObject;

class GeneratorDependencies
{
    /**
     * @var HistoryManager
     */
    private $historyManager = null;
    /**
     * @var CreateCommand
     */
    private $createCommand = null;

    /**
     * @param HistoryManager $historyManager
     * @param CreateCommand $createCommand
     */
    public function __construct(
        HistoryManager $historyManager,
        $createCommand
    ) {
        $this->historyManager = $historyManager;
        $this->createCommand  = $createCommand;
    }

    /**
     * @param string $codeGeneratorName
     * @throws CodeGeneratorNotFoundException
     */
    private function codeGeneratorIsset($codeGeneratorName)
    {
    	$classAwake = new \CrudGenerator\Utils\ClassAwake();
    	$classAwake->wakeByParent(array(__DIR__ . '/'), 'CrudGenerator\Generators\BaseCodeGenerator');
        if (!class_exists($codeGeneratorName, false)) {
            throw new CodeGeneratorNotFoundException(
                sprintf(
                    'Code generator with name "%s" does not exist',
                    $codeGeneratorName
                )
            );
        }
    }

    /**
     * @param string $codeGeneratorName
     * @throws GeneratorDependenciesNotFound
     * @throws CodeGeneratorNotFoundException
     * @return \CrudGenerator\DataObject
     */
    public function findDependencie($codeGeneratorName)
    {
        $this->codeGeneratorIsset($codeGeneratorName);
        $history = $this->historyManager->find($codeGeneratorName);

        foreach ($history->getDataObjects() as $dto) {
            if (get_class($dto) === $codeGeneratorName) {
                return $dto;
            }
        }

        throw new GeneratorDependenciesNotFound();
    }

    /**
     * @param DataObject $dto
     * @param string $codeGeneratorName
     * @return \CrudGenerator\DataObject
     */
    public function create(DataObject $dto, $codeGeneratorName)
    {
        $this->codeGeneratorIsset($codeGeneratorName);
        return $this->createCommand->create($dto, $codeGeneratorName);
    }
}