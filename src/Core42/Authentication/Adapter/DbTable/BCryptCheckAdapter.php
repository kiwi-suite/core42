<?php
namespace Core42\Authentication\Adapter\DbTable;

use Core42\Db\TableGateway\AbstractTableGateway;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Zend\Crypt\Password\Bcrypt;

class BCryptCheckAdapter extends CallbackCheckAdapter
{
    public function __construct(array $options = array())
    {
        if (!isset($options['table_gateway']) || !($options['table_gateway'] instanceof AbstractTableGateway)) {
            throw new \Exception("no or invalid table_gateway options for authentication");
        }
        $tableGateway = $options['table_gateway'];

        if (empty($options['identity_column'])) {
            throw new \Exception("invalid identity_column option");
        }
        $identityColumn = $options['identity_column'];

        if (empty($options['credential_column'])) {
            throw new \Exception("invalid credential_column option");
        }
        $credentialColumn =  $options['credential_column'];

        parent::__construct($tableGateway->getAdapter(), $tableGateway->getTable(), $identityColumn, $credentialColumn, array($this, 'bcryptValidationCallback'));
    }

    /**
     * @param  string $dbPassword
     * @param  string $userPassword
     * @return bool
     */
    public function bcryptValidationCallback($dbPassword, $userPassword)
    {
        $bcrypt = new Bcrypt();

        return $bcrypt->verify($userPassword, $dbPassword);
    }
}
