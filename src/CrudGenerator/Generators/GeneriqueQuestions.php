<?php

namespace CrudGenerator\Generators;

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use CrudGenerator\DataObject;

class GeneriqueQuestions
{
    private $dialog            = null;
    private $output            = null;
    private $directoryResponse = null;
    private $namespaceResponse = null;

    public function __construct(DialogHelper $dialog, OutputInterface $output)
    {
        $this->dialog = $dialog;
        $this->output = $output;
    }

    public function directoryQuestion(DataObject $dataObject)
    {
        if(null === $this->directoryResponse) {
            $moduleName = $dataObject->getModule();
            $directoryValidation = function ($directory) use ($moduleName) {
                if (!is_dir($moduleName . '/' . $directory)) {
                    throw new \InvalidArgumentException(sprintf('Directory "%s" does not exist.', $moduleName . $directory));
                }

                return $directory;
            };

            $this->directoryResponse = $this->dialog->askAndValidate($this->output, 'Choose a target directory ', $directoryValidation, false, null);
        }

        return $this->directoryResponse;
    }

    public function namespaceQuestion()
    {
        if(null === $this->namespaceResponse) {
            $this->namespaceResponse   = $this->dialog->ask($this->output, 'Choose a target namespace ');
        }

        return $this->namespaceResponse;
    }
}