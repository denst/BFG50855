<?

class Controller_etc extends Template {

    function before() {
        parent::before();
        $this->auto_render = false;
    }

    // Имитация файла robots.txt
    function action_robots() {
        $this->response->headers('Content-Type','text/plain; charset=utf-8');

        $host = Arr::get($_SERVER,'HTTP_HOST');
        $this->response->body('User-agent: *'
            ."\n".'Sitemap: http://'.$host.'/sitemap.xml'
            ."\n".'host: '. $host);
    }

    // Имитация файла sitemap.xml
    function action_sitemap() {
        $this->response->headers('Content-Type','text/plain; charset=utf-8');

        $host = Arr::get($_SERVER,'HTTP_HOST');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            ."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $mainlinks = array(
            ADVERTS_CATS_FLAT_STR . '/' . ADVERTS_TYPES_PRODAJA_STR,
            ADVERTS_CATS_HOUSE_STR . '/' . ADVERTS_TYPES_PRODAJA_STR,
            ADVERTS_CATS_GARAGE_STR . '/' . ADVERTS_TYPES_PRODAJA_STR,
            ADVERTS_CATS_ROOM_STR . '/' . ADVERTS_TYPES_PRODAJA_STR,
            ADVERTS_CATS_TERRAIN_STR . '/' . ADVERTS_TYPES_PRODAJA_STR,
            ADVERTS_CATS_COMMERCE_STR . '/' . ADVERTS_TYPES_PRODAJA_STR,

            ADVERTS_CATS_FLAT_STR . '/' . ADVERTS_TYPES_ARENDA_STR,
            ADVERTS_CATS_HOUSE_STR . '/' . ADVERTS_TYPES_ARENDA_STR,
            ADVERTS_CATS_GARAGE_STR . '/' . ADVERTS_TYPES_ARENDA_STR,
            ADVERTS_CATS_ROOM_STR . '/' . ADVERTS_TYPES_ARENDA_STR,
            ADVERTS_CATS_TERRAIN_STR . '/' . ADVERTS_TYPES_ARENDA_STR,
            ADVERTS_CATS_COMMERCE_STR . '/' . ADVERTS_TYPES_ARENDA_STR,
        );

        foreach($mainlinks as $link) {
            $xml .=  "\n".'<url>
    <loc>http://'.$host.'/'.$link.'</loc>
    <changefreq>weekly</changefreq>
</url>';
        }

        $pages = ORM::factory('page')->find_all();

        foreach($pages as $page) {
            $xml .=  "\n".'<url>
    <loc>http://'.$host.'/'.$page->lat_name.'</loc>
    <changefreq>weekly</changefreq>
</url>';
        }

        $posts = ORM::factory('advert')->filterByLocation($this->location)->find_all();

        foreach($posts as $post) {
            $xml .=  "\n".'<url>
    <loc>'.$post->link().'</loc>
    <changefreq>weekly</changefreq>
</url>';
        }

        $xml .= "\n".'</urlset>';

        $this->response->body($xml);
    }
}

?>
