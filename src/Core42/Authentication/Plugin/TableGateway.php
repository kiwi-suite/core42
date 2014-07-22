<?php
namespace Core42\Authentication\Plugin;

use Core42\Permissions\Rbac\Role\RoleAwareInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Core42\Db\TableGateway\AbstractTableGateway;
use Core42\Model\AbstractModel;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container as SessionContainer;

class TableGateway implements AdapterInterface, StorageInterface, PluginInterface, RoleAwareInterface
{
    /**
     * @var AbstractTableGateway
     */
    protected $tableGateway;

    /**
     * @var string
     */
    protected $identityColumn;

    /**
     * @var string
     */
    protected $credentialColumn;

    /**
     * @var string
     */
    protected $identity;

    /**
     * @var AbstractModel
     */
    protected $user;

    /**
     * @var SessionContainer
     */
    protected $session;

    /**
     * @var string
     */
    protected $credential;

    public function __construct(AbstractTableGateway $tableGateway = null,
                                $identityColumn = null, $credentialColumn = null)
    {
        if ($tableGateway !== null) {
            $this->setTableGateway($tableGateway);
        }

        if ($identityColumn !== null) {
            $this->setIdentityColumn($identityColumn);
        }

        if ($credentialColumn !== null) {
            $this->setCredentialColumn($credentialColumn);
        }
    }

    public function setOptions(array $options, ServiceManager $serviceManager)
    {
        if (isset($options['table_gateway'])) {
            $this->setTableGateway($serviceManager->get('TableGateway')->get($options['table_gateway']));
        }
        if (isset($options['identity_column'])) {
            $this->setIdentityColumn($options['identity_column']);
        }
        if (isset($options['credential_column'])) {
            $this->setCredentialColumn($options['credential_column']);
        }
    }

    /**
     * @param  AbstractTableGateway                       $tableGateway
     * @return \Core42\Authentication\Plugin\TableGateway
     */
    public function setTableGateway(AbstractTableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;

        return $this;
    }

    /**
     * @return AbstractTableGateway
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    /**
     * @param  string $identityColumn
     * @return \Core42\Authentication\Plugin\TableGateway
     */
    public function setIdentityColumn($identityColumn)
    {
        $this->identityColumn = $identityColumn;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentityColumn()
    {
        return $this->identityColumn;
    }

    /**
     * @param string $credentialColumn
     * @return \Core42\Authentication\Plugin\TableGateway
     */
    public function setCredentialColumn($credentialColumn)
    {
        $this->credentialColumn = $credentialColumn;

        return $this;
    }

    /**
     * @return string
     */
    public function getCredentialColumn()
    {
        return $this->credentialColumn;
    }

    /**
     * @param $identity
     * @return \Core42\Authentication\Plugin\TableGateway
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param $credential
     * @return \Core42\Authentication\Plugin\TableGateway
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;

        return $this;
    }

    /**
     * @return string
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * @param  AbstractModel                              $user
     * @return \Core42\Authentication\Plugin\TableGateway
     */
    public function setUser(AbstractModel $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return AbstractModel
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        $resultSet = $this->getTableGateway()->select(array(
            $this->getIdentityColumn() => $this->getIdentity()
        ));

        if ($resultSet->count() == 0) {
            $this->onIdentityNotFound();

            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                $this->getIdentity(),
                array('Supplied credential is invalid.')
            );
        }
        if ($resultSet->count() > 1) {
            $this->onIdentityAmbiguous();

            return new Result(Result::FAILURE_IDENTITY_AMBIGUOUS, $this->getIdentity());
        }

        $this->setUser($resultSet->current());
        $userArray = $this->getUser()->extract();

        if (!$this->matchPassword($userArray[$this->getCredentialColumn()], $this->getCredential())) {
            return new Result(
                Result::FAILURE_CREDENTIAL_INVALID,
                $this->getIdentity(),
                array('Supplied credential is invalid.')
            );
        }

        return new Result(
            Result::SUCCESS,
            $userArray[current($this->getTableGateway()->getPrimaryKey())]
        );
    }

    protected function onIdentityNotFound()
    {
        $bcrypt = new Bcrypt();
        $bcrypt->create("test");
    }

    protected function onIdentityAmbiguous()
    {
        $bcrypt = new Bcrypt();
        $bcrypt->create("test");
    }

    protected function matchPassword($realPassword, $inputPassword)
    {
        $bcrypt = new Bcrypt();

        return $bcrypt->verify($inputPassword, $realPassword);
    }

    /**
     * @return SessionContainer
     */
    public function getSessionContainer()
    {
        if ($this->session == null) {
            $this->session = new SessionContainer("Core42_Auth");
        }

        return $this->session;
    }

    /**
     * Returns true if and only if storage is empty
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If it is impossible to determine whether storage is empty
     * @return bool
     */
    public function isEmpty()
    {
        return !(isset($this->getSessionContainer()->storage));
    }

    /**
     * Returns the contents of storage
     *
     * Behavior is undefined when storage is empty.
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If reading contents from storage is impossible
     * @return AbstractModel
     */
    public function read()
    {
        $user = $this->getUser();
        if ($user instanceof AbstractModel) {
            return $user;
        }
        $userId = $this->getSessionContainer()->storage;
        $user = $this->getTableGateway()->selectByPrimary($userId);
        if (empty($user)) {
            $this->clear();

            return null;
        }

        $this->setUser($user);

        return $this->getUser();
    }

    /**
     * Writes $contents to storage
     *
     * @param  mixed                                             $contents
     * @throws \Zend\Authentication\Exception\ExceptionInterface If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents)
    {
        $this->getSessionContainer()->storage = $contents;
    }

    /**
     * Clears contents from storage
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface If clearing contents from storage is impossible
     * @return void
     */
    public function clear()
    {
        unset($this->getSessionContainer()->storage);
    }

    public function getIdentityRole()
    {
        if ($this->isEmpty()) {
            return null;
        }

        $user = $this->read();
        if ($user instanceof RoleAwareInterface) {
            return $user->getIdentityRole();
        }

        return null;
    }
}
