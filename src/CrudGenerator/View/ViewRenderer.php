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

use CrudGenerator\View\ViewRendererException;

/**
 * Template renderer
 *
 * @author Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 */
class ViewRenderer
{
    /**
     * Interprete the file
     *
     * @param string $path
     * @param string $templateName
     * @throws Exception
     * @return string
     */
    public function render($path, $templateName)
    {
        try {
            ob_start();
            include $path . $templateName;
            $content = ob_get_clean();
        } catch (\Exception $ex) {
            ob_end_clean();
            throw new ViewRendererException(
                'In : "' . $path . $templateName . '" ' . $ex->getMessage() . ' Line ' . $ex->getLine()
            );
        }

        return $content;
    }
}
