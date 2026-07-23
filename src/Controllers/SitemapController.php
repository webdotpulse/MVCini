<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Item;

class SitemapController extends Controller
{
    public function index()
    {
        global $config;
        $baseUrl = $config['base_url'] ?? 'http://localhost:8000';

        $urls = [
            '/',
            '/admin/login',
            '/contact' // to be implemented
        ];

        // Add dynamic URLs, e.g., items
        $items = Item::all();
        foreach ($items as $item) {
            $urls[] = '/item/view/' . $item['id']; // Hypothetical view endpoint
        }

        header('Content-Type: application/xml; charset=utf-8');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($baseUrl . $url) . "</loc>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.8</priority>\n";
            echo "  </url>\n";
        }

        echo '</urlset>';
        exit;
    }
}
