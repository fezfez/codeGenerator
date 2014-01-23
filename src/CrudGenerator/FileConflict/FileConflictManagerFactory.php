<?php
namespace CrudGenerator\FileConflict;

use CrudGenerator\Utils\FileManager;
use SebastianBergmann\Diff\Differ;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Context\CliContext;


class FileConflictManagerFactory
{
    /**
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @return \CrudGenerator\FileConflict\FileConflictManager
     */
    public static function getInstance(ContextInterface $context)
    {
    	if ($context instanceof WebContext) {
    		return new FileConflictManagerWeb(
    			new FileManager(),
    			new Differ()
    		);
    	} elseif ($context instanceof CliContext) {
    		return new FileConflictManagerCli(
    			$context->getOutput(),
    			$context->getDialogHelper(),
    			new FileManager(),
    			new Differ()
    		);
    	}
    }
}