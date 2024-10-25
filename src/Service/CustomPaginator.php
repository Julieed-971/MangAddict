<?php
namespace App\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CustomPaginator
{
    private PaginatorInterface $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function paginate(array $items, int $page, int $limit): PaginationInterface
    {
		/**
		 * Paginates the given items based on the specified page and limit.
		 *
		 * @param array $items The array of items to paginate.
		 * @param int $page The current page number.
		 * @param int $limit The number of items per page.
		 * @return array The paginated items.
		 */
        // $offset = ($page - 1) * $limit;
        // $paginatedItems = array_slice($items, $offset, $limit);

        return $this->paginator->paginate($items, $page, $limit, [
            'totalItems' => count($items),
        ]);
    }
}