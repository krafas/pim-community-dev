<?php

namespace Pim\Bundle\CatalogBundle\Doctrine\ORM\Filter;

use Pim\Bundle\CatalogBundle\Doctrine\Query\FieldFilterInterface;
use Pim\Bundle\CatalogBundle\Doctrine\ORM\Join\CompletenessJoin;
use Doctrine\ORM\QueryBuilder;

/**
 * Completeness filter
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CompletenessFilter implements FieldFilterInterface
{
    /**
     * @var QueryBuilder
     */
    protected $qb;

    /** @var array */
    protected $supportedOperators;

    /**
     * Instanciate a sorter
     */
    public function __construct()
    {
        $this->supportedOperators = ['=', '<'];
    }

    /**
     * {@inheritdoc}
     */
    public function addFieldFilter($field, $operator, $value, $context = [])
    {
        $alias = 'filterCompleteness';
        $field = $alias.'.ratio';
        $util = new CompletenessJoin($this->qb);
        $util->addJoins($alias);

        if ($operator === '=') {
            $this->qb->andWhere($this->qb->expr()->eq($field, '100'));
        } else {
            $this->qb->andWhere($this->qb->expr()->lt($field, '100'));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setQueryBuilder($queryBuilder)
    {
        $this->qb = $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsField($field)
    {
        return $field === 'completeness';
    }

    /**
     * {@inheritdoc}
     */
    public function supportsOperator($operator)
    {
        return in_array($operator, $this->supportedOperators);
    }

    /**
     * {@inheritdoc}
     */
    public function getOperators()
    {
        return $this->supportedOperators;
    }
}
