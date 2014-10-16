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
namespace CrudGenerator\Storage;

class StorageArray implements \JsonSerializable
{
    private $store = array();

    /**
     * @param array $args
     * @return boolean
     */
    public function isValidStore(array $args)
    {
        if (count($args) === 2) {
            return true;
        }

        return false;
    }

    /**
     * @param array $args
     * @return boolean
     */
    public function isValidAcces(array $args)
    {
        if (count($args) < 2) {
            return true;
        }

        return false;
    }

    /**
     * @param array $args
     */
    public function set(array $args)
    {
        $this->store[$args[0]] = $args[1];
    }

    /**
     * @param array $args
     * @return mixed
     */
    public function get(array $args)
    {
        if (count($args) === 0) {
            return $this->store;
        } elseif (isset($this->store[$args[0]]) === true) {
            return $this->store[$args[0]];
        } else {
            return null;
        }
    }

    /**
     *
     */
    public function jsonSerialize()
    {
        return $this->store;
    }
}
