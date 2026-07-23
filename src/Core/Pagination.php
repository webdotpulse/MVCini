<?php
namespace App\Core;

class Pagination
{
    /**
     * Render semantic HTML pagination links, compatible with Tailwind CSS styling
     *
     * @param int $currentPage
     * @param int $totalPages
     * @return string
     */
    public static function links(int $currentPage, int $totalPages): string
    {
        if ($totalPages <= 1) {
            return '';
        }

        $queryParams = $_GET;

        $html = '<nav class="flex items-center justify-center space-x-2 my-4" aria-label="Pagination">';

        // Previous link
        if ($currentPage > 1) {
            $queryParams['page'] = $currentPage - 1;
            $prevUrl = '?' . http_build_query($queryParams);
            $html .= '<a href="' . htmlspecialchars($prevUrl) . '" class="px-3 py-2 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Previous</a>';
        } else {
            $html .= '<span class="px-3 py-2 border rounded-md text-sm font-medium text-gray-400 bg-gray-50 cursor-not-allowed">Previous</span>';
        }

        // Page numbers
        for ($i = 1; $i <= $totalPages; $i++) {
            $queryParams['page'] = $i;
            $url = '?' . http_build_query($queryParams);

            if ($i === $currentPage) {
                $html .= '<span class="px-3 py-2 border rounded-md text-sm font-medium text-white bg-blue-600 border-blue-600" aria-current="page">' . $i . '</span>';
            } else {
                $html .= '<a href="' . htmlspecialchars($url) . '" class="px-3 py-2 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">' . $i . '</a>';
            }
        }

        // Next link
        if ($currentPage < $totalPages) {
            $queryParams['page'] = $currentPage + 1;
            $nextUrl = '?' . http_build_query($queryParams);
            $html .= '<a href="' . htmlspecialchars($nextUrl) . '" class="px-3 py-2 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Next</a>';
        } else {
            $html .= '<span class="px-3 py-2 border rounded-md text-sm font-medium text-gray-400 bg-gray-50 cursor-not-allowed">Next</span>';
        }

        $html .= '</nav>';

        return $html;
    }
}
