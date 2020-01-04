<?php declare(strict_types=1);


namespace Minms\SwoftContracts\Beans;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Concern\PrototypeTrait;
use Swoft\Context\Context;
use Swoft\Stdlib\Contract\Arrayable;

/**
 * Class Pagination
 * @package Minms\SwoftContracts\Beans
 *
 * @Bean(name="pagination", scope=Bean::PROTOTYPE)
 */
class Pagination implements Arrayable
{
    use PrototypeTrait;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $pageSize;

    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $totalPage;

    /**
     * Create a new collection.
     *
     * @param int $total
     * @param int $pageSize
     * @return Pagination
     */
    public static function new(int $total, int $pageSize = null): self
    {
        $self = self::__instance();

        $request        = Context::get()->getRequest();
        $self->page     = (int)$request->get('page');
        $self->page     = $self->page < 1 ? 1 : $self->page;
        $self->pageSize = $pageSize ?? (int)$request->get('pageSize', 15);

        $self->total     = $total;
        $self->totalPage = ceil($self->total / $self->pageSize);

        return $self;
    }

    public function toArray(): array
    {
        return [
            'page'      => $this->page,
            'pageSize'  => $this->pageSize,
            'pageCount' => $this->totalPage,
            'total'     => $this->total,
        ];
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }
}