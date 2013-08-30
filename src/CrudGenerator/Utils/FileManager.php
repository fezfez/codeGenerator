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
namespace CrudGenerator\Utils;

use RuntimeException;

/**
 * Manage file and directory
 *
 * @author Stéphane Demonchaux
 */
class FileManager
{
    /**
     * Create a directory
     * @param string $pathname Dir to create
     * @throws RuntimeException
     */
    public function mkdir($pathname)
    {
        if (!@mkdir($pathname, 0777)) {
            throw new RuntimeException(sprintf("Could't create %s", $pathname));
        }
    }

    /**
     * Puts content into file
     * @param string $path File path
     * @param string $content File Content
     */
    public function filePutsContent($path, $content)
    {
        if (@file_put_contents($path, $content) === false) {
            throw new \RuntimeException(sprintf("Could't puts content %s", $path));
        }

        chmod($path, 0777);
    }

    /**
     * Get content from file
     * @param string $path File path
     */
    public function fileGetContent($path)
    {
        $return = @file_get_contents($path);
        if ($return === false) {
            throw new \RuntimeException(sprintf("Could't load content %s", $path));
        }
        return $return;
    }

    /**
     * Test if is file
     * @param string $path File path
     */
    public function isFile($path)
    {
        return is_file($path);
    }

    /**
     * Test if is directory
     * @param string $path Directory path
     */
    public function isDir($path)
    {
        return is_dir($path);
    }

    /**
     * Test if file exist
     * @param string $file File path
     */
    public function fileExists($file)
    {
        return file_exists($file);
    }

    /**
     * Include file
     * @param string $file File path
     */
    public function includeFile($file)
    {
        return include $file;
    }

    /**
     * Delete file
     * @param string $file File path
     */
    public function unlink($file)
    {
        if (@unlink($file) === false) {
            throw new \RuntimeException(sprintf("Could't delete %s", $file));
        }
    }

    /**
     * Glob dir
     * @param string $dir Dir path
     */
    public function glob($dir, $params = null)
    {
        return glob($dir, $params);
    }
}
