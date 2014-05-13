<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace CrudGenerator\Context;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

class CliContext implements ContextInterface
{
	/**
	 * @var OutputInterface
	 */
	private $output       = null;
	/**
	 * @var DialogHelper
	 */
	private $dialogHelper = null;

	/**
	 * @param DialogHelper $dialogHelper
	 * @param OutputInterface $output
	 */
	public function __construct(DialogHelper $dialogHelper, OutputInterface $output)
	{
		$this->dialogHelper = $dialogHelper;
		$this->output  = $output;
	}

	/**
	 * @return \Symfony\Component\Console\Helper\DialogHelper
	 */
	public function getDialogHelper()
	{
		return $this->dialogHelper;
	}

	/**
	 * @return \Symfony\Component\Console\Output\OutputInterface
	 */
	public function getOutput()
	{
		return $this->output;
	}

	/* (non-PHPdoc)
	 * @see \CrudGenerator\Context\ContextInterface::ask()
	 */
	public function ask($text, $attribute)
	{
		return $this->dialog->ask($this->output, '<question>Choose a "' . $propName . '"</question> : ');
	}

	/* (non-PHPdoc)
	 * @see \CrudGenerator\Context\ContextInterface::ask()
	*/
	public function askCollection($text, $uniqueKey, array $collection)
	{
		$this->question[$uniqueKey] = $collection;

		return $this->dialog->select($this->output, '<question>Choose a "' . $text . '"</question> : ', $collection);
	}
}