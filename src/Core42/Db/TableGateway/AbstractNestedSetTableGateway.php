<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\TableGateway;

use Core42\Model\NestedSetInterface;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

abstract class AbstractNestedSetTableGateway extends AbstractTableGateway
{

    public function checkNestedSet()
    {
        $primary = $this->getPrimaryKey();

        /* @var NestedSetInterface $model */
        $model = $this->getModelPrototype();

        $select = $this->sql->select();
        $select->columns(array(
            'max' => new Expression("MAX({$model->getNestedRightFieldName()})"),
            'count' => new Expression("COUNT({$model->getNestedRightFieldName()})"),
        ));

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        if ($result->count() == 1) {
            $row = $result->current();
            if ($row['max'] != $row['count'] * 2) {
                echo 'max passt nicht zu count';
            }
        }

        // detect gaps or duplicate values
        $selectLeft = $this->sql->select();
        $selectLeft->columns(array_merge(array_values($primary), array('nested' => $model->getNestedLeftFieldName())));

        $selectRight = $this->sql->select();
        $selectRight->columns(array_merge(array_values($primary), array('nested' => $model->getNestedRightFieldName())));

        $selectLeft->combine($selectRight);
        $selectCombined = (new Select())->from(['sub' => $selectLeft])->order('nested');

        $statement = $this->sql->prepareStatementForSqlObject($selectCombined);
        $result = $statement->execute();

        $last = 0;
        foreach ($result as $row) {
            $n = (int) $row['nested'];
            if ($last + 1 != $n) {
                echo 'gap or duplicated value detected at ';
                break;
            }
            $last = $n;
        }

    }

    public function regenerateNestedSet()
    {
        $this->adapter->driver->getConnection()->beginTransaction();

        $select = $this->sql->select();
        $select->order(array($this->parentFieldName, $this->sortFieldName));

        $result = $this->selectWith($select);

        $children = array();
        $items = array();
        $rootItems = array();

        foreach ($result as $row) {
            /* @var NestedSetInterface $row */
            $parentId = $row->getParentId();
            if (!empty($parentId)) {
                if (!array_key_exists($parentId, $children)) {
                    $children[$parentId] = array();
                }
                $children[$parentId][] = $row->getId();
            } else {
                $rootItems[] = $row->getId();
            }
            $items[$row->getId()] = $row;
        }

        $left = 1;
        $right = 2;
        foreach ($rootItems as $id) {
            $r = $this->regenerateRecursive($id, $left, $right, $items, $children);

            $left = $r['right'] + 1;
            $right = $left + 1;
        }

        $this->adapter->driver->getConnection()->commit();
    }

    protected function regenerateRecursive($itemId, $left, $right, &$items, $children)
    {
        $myLeft = $left;
        $myRight = $right;

        if (!empty($children[$itemId])) {
            $myLeft = $left;

            $left++;
            foreach ($children[$itemId] as $child) {
                $right = $left + 1;
                $r = $this->regenerateRecursive($child, $left, $right, $items, $children);

                $left = $r['right'] + 1;
            }
            $myRight = $left;
        }

        /* @var NestedSetInterface $row */
        $row = $items[$itemId];
        $row->setNestedLeft($myLeft);
        $row->setNestedRight($myRight);

        parent::update($row);

        return array(
            'left' => $myLeft,
            'right' => $myRight
        );
    }
}
