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
namespace CrudGenerator\View;

use CrudGenerator\FileManager;
use CrudGenerator\View\ViewRenderer;

/**
 * Manage template renderer
 *
 * @author Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 */
class View
{
    /**
     * @var FileManager File manager
     */
    private $fileManager = null;
    /**
     * @var ViewRenderer File interpreter
     */
    private $viewRenderer = null;

    /**
     * Manage template renderer
     *
     * @param FileManager $fileManager
     * @param ViewRenderer $viewRenderer
     */
    public function __construct(FileManager $fileManager, ViewRenderer $viewRenderer)
    {
        $this->fileManager  = $fileManager;
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * Render the template
     *
     * @param string $path
     * @param string $templateName
     * @param array $datas
     */
    public function render($path, $templateName, array $datas)
    {
        $viewRenderer = clone $this->viewRenderer;
        foreach($datas as $name => $data) {
            $viewRenderer->$name = $data;
        }

        return $viewRenderer->render($path, $templateName);
    }
}
