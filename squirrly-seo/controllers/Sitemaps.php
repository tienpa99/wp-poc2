<?php
defined('ABSPATH') || die('Cheatin\' uh?');

/**
 * Class for Sitemap Generator
 */
class SQ_Controllers_Sitemaps extends SQ_Classes_FrontController
{
    /* @var string root name */
	private $root = 'sitemap';

	private $sitemap = false;
	private $type = false;
	private $taxonomy = false;
	private $page = 0;

    /* @var string post limit */
	private $posts_limit;

    public function __construct()
    {
        parent::__construct();

		//set the limits
        $this->posts_limit = SQ_Classes_Helpers_Tools::getOption('sq_sitemap_perpage');

		//load the sitemap if there are xml calls
	    add_action( 'init', array($this, 'initSitemap'));

		//Check the trailing
        add_filter('user_trailingslashit', array($this, 'untrailingslashit'));

		//Add the Squirrly style on sitemap
	    add_filter('sq_sitemap_style', array($this, 'getSquirrlyHeader'));

        //Process the cron if created
        add_action('sq_processPing', array($this, 'processCron'));
    }

	/**
	 * Load the sitemap
	 *
	 * @return mixed|void
	 */
    public function initSitemap()
    {
        global $wp_query;

        if (isset($_SERVER['REQUEST_URI'])) {

	        if (strpos($_SERVER['REQUEST_URI'], 'locations.kml') !== false) {
		        if (SQ_Classes_Helpers_Tools::getOption('sq_jsonld_type') == 'Organization') {
			        $wp_query->is_404 = false;
			        $wp_query->is_feed = true;

			        //set current type
			        $this->model->setCurrentSitemap('locations');

			        //show the sitemap
			        $this->showSitemap();
		        }
	        }

            if (isset($_SERVER['QUERY_STRING']) && strpos($_SERVER['QUERY_STRING'], 'sq_feed') !== false) {
                parse_str($_SERVER['QUERY_STRING'], $query);

                if (isset($query['sq_feed']) && strpos($query['sq_feed'], 'sitemap') !== false) {
					//set current sitemap params
	                $this->sitemap  = $query['sq_feed'];
	                $this->page     = ( isset( $query['page'] ) ? $query['page'] : 0 );
	                $this->type     = ( isset( $query['type'] ) ? $query['type'] : false );
	                $this->taxonomy = ( isset( $query['taxonomy'] ) ? $query['taxonomy'] : false );

					//set the sitemap type
	                $this->model->setCurrentSitemap($this->sitemap);

	                $wp_query->is_404  = false;
	                $wp_query->is_feed = true;

                }

            } elseif (strpos($_SERVER['REQUEST_URI'], '.xml') !== false) {
                //Compatibility with other SEO plugins sitemap
                if(strpos($_SERVER['REQUEST_URI'], '/sitemap_index.xml')){
                    $_SERVER['REQUEST_URI'] = str_replace('sitemap_index.xml','sitemap.xml',$_SERVER['REQUEST_URI']);
                }

                $parseurl = parse_url($_SERVER['REQUEST_URI']);
	            $stemaplist = SQ_Classes_Helpers_Tools::getOption('sq_sitemap');

                foreach ($stemaplist as $request => $sitemap) {
                    if (isset($sitemap[0]) && $sitemap[1] && substr($parseurl['path'], (strrpos($parseurl['path'], '/') + 1)) == $sitemap[0]) {

						//set current sitemap
	                    $this->sitemap = $request;

	                    //set the sitemap type
	                    $this->model->setCurrentSitemap($this->sitemap);

                        $wp_query->is_404 = false;
                        $wp_query->is_feed = true;

                        if (isset($parseurl['query'])) {
                            parse_str($parseurl['query'], $query);

							//set sitemap params
	                        $this->page     = (isset($query['page']) ? $query['page'] : 0);
	                        $this->type     = (isset($query['type']) ? $query['type'] : false);
	                        $this->taxonomy = (isset($query['taxonomy']) ? $query['taxonomy'] : false);

                        }

                    }
                }
            }

			if($this->sitemap) {

				//check if the sitemap is set to late loading
				if(apply_filters('sq_lateloading_sitemap', false)){
					add_action( 'wp', array($this, 'feedRequest'));
					add_action( 'wp', array($this, 'showSitemap'));
				}else{
					$this->feedRequest();
					$this->showSitemap();
				}

			}

        }
    }

    /**
     * Send the sitemap to Search Engines only if a page is freshly posted
     *
     * @param $new_status
     * @param $old_status
     * @param $post
     */
    public function refreshSitemap($new_status, $old_status, $post)
    {
        if ($old_status <> $new_status && $new_status = 'publish') {
            if (SQ_Classes_Helpers_Tools::getOption('sq_sitemap_ping')) {
                wp_schedule_single_event(time() + 5, 'sq_processPing');
            }
        }
    }

    /**
     * Process the sitemap query
     *
     */
    public function feedRequest()
    {
        global $sq_query;
        $sq_query = array();

        $sq_sitemap = SQ_Classes_Helpers_Tools::getOption('sq_sitemap');

		//prepare the sitemap language
	    $this->model->setCurrentLanguage();
	    $language = $this->model->getLanguage();

		//don't load on sitemap index
	    if($this->sitemap == 'sitemap'){
			return;
	    }

		//reset the previous query
        wp_reset_query();
        remove_all_actions('pre_get_posts');
        remove_all_actions('parse_query');
        remove_all_actions('posts_where');

        //init the query
        $sq_query = array(
            'post_type' => array('post'),
            'tax_query' => array(),
            'post_status' => 'publish',
            'posts_per_page' => 500,
            'paged' => $this->page,
            'orderby' => 'date',
            'order' => 'DESC',
        );

        if ($language <> '') {
            if (!SQ_Classes_Helpers_Tools::getOption('sq_sitemap_combinelangs')) {
                $sq_query['lang'] = apply_filters('sq_sitemap_language', $language);
            }
        }

        //show products
        if ($this->sitemap == 'sitemap-product') {
            if (SQ_Classes_Helpers_Tools::isEcommerce() && $sq_sitemap[$this->sitemap][1] == 2) {
                $sq_sitemap[$this->sitemap][1] = 1;
            }
        }

        if (isset($sq_sitemap[$this->sitemap]) && $sq_sitemap[$this->sitemap][1]) {

            //PREPARE CUSTOM QUERIES
            switch ($this->sitemap) {
                case 'sitemap-news':
                    //integration with webstory plguin for Google News
                    foreach (SQ_Classes_Helpers_Tools::getOption('patterns') as $pattern => $pattern_type) {
                        if(isset($pattern_type['google_news']) && $pattern_type['google_news'] == 1){
                            array_push($sq_query['post_type'],$pattern);
                            $sq_query['post_type'] = array_unique($sq_query['post_type']);
                        }
                    }
                    $sq_query['date_query'] = array(
                        'after'     => 'midnight 2 days ago',
                        'inclusive' => true,
                    );
                case 'sitemap-post':
                    $sq_query['posts_per_page'] = $this->posts_limit;
                    break;
                case 'sitemap-category':
                case 'sitemap-post_tag':
                case 'sitemap-custom-tax':
                    remove_all_filters('terms_clauses'); //prevent language filters
                    add_filter('get_terms_fields', array($this, 'customTaxFilter'), 5, 2);
                    if($this->taxonomy) {
                        $sq_query['hide_empty'] = true;
                        $sq_query['number'] = $this->posts_limit;
                        $sq_query['offset'] = ( $this->page * $this->posts_limit );
                        $sq_query['taxonomy'] = $this->taxonomy;
                    }else{
                        $sq_query['hide_empty'] = true;
                        $sq_query['taxonomy'] = str_replace('sitemap-', '', $this->sitemap);
                    }

                    break;
                case 'sitemap-page':
                    $sq_query['post_type'] = array('page');
                    $sq_query['posts_per_page'] = $this->posts_limit;
                    break;
                case 'sitemap-attachment':
                    $sq_query['post_type'] = array('attachment');
                    $sq_query['post_status'] = array('publish', 'inherit');
                    break;
                case 'sitemap-author':
                    add_filter('sq-sitemap-authors', array($this, 'authorFilter'), 5);
                    break;
                case 'sitemap-custom-post':
                    if($this->type) {
                        $sq_query['post_type'] = array($this->type);
                        $sq_query['posts_per_page'] = $this->posts_limit;
                        $sq_query['offset'] = ( $this->page * $this->posts_limit );
                    }else {
                        $types = get_post_types(array('public' => true));
                        foreach (array('post', 'page', 'attachment', 'revision', 'nav_menu_item', 'product', 'wpsc-product', 'ngg_tag', 'elementor_library','ct_template','oxy_user_library','fusion_template') as $exclude) {
                            if (in_array($exclude, $types)) {
                                unset($types[$exclude]);
                            }
                        }

                        foreach ($types as $type) {
                            $type_data = get_post_type_object($type);
                            if ((isset($type_data->rewrite['publicly_queryable']) && $type_data->rewrite['publicly_queryable'] == 1) || (isset($type_data->publicly_queryable) && $type_data->publicly_queryable == 1)) {
                                continue;
                            }
                            unset($types[$type]);
                        }

                        if (empty($types)) {
                            array_push($types, 'custom-post');
                        }

                        $sq_query['post_type'] = $types;
                        $sq_query['posts_per_page'] = $this->posts_limit;
                        $sq_query['offset'] = ( $this->page * $this->posts_limit );
                    }

                    break;
                case 'sitemap-product':
                    if (SQ_Classes_Helpers_Tools::isEcommerce()) {
                        $types = array('product', 'wpsc-product');
                    } else {
                        $types = array('custom-post');
                    }
                    $sq_query['post_type'] = $types;
                    $sq_query['posts_per_page'] = $this->posts_limit;

                    break;
                case 'sitemap-archive':
                    if($this->type) {
                        $sq_query['post_type'] = array($this->type);
                    }else{
                        $sq_query['post_type'] = array('post');
                    }
                    add_filter('sq-sitemap-archive', array($this, 'archiveFilter'), 5);
                    break;
            }

            //add custom filter
            do_action('do_feed_' . $this->sitemap);
        }

    }

    public function getSquirrlyHeader($header)
    {

        if ($this->sitemap <> 'locations') {
            $header = '<?xml-stylesheet type="text/xsl" href="/' . _SQ_ASSETS_RELATIVE_URL_ . 'css/sitemap' . ($this->sitemap == 'sitemap' ? 'index' : ($this->sitemap == 'sitemap-news' ? 'news' : '')) . '.xsl"?>' . "\n";
            $header .= '<!-- generated-on="' . date('Y-m-d\TH:i:s+00:00') . '" -->' . "\n";
            $header .= '<!-- generator="Squirrly SEO Sitemap" -->' . "\n";
            $header .= '<!-- generator-url="https://wordpress.org/plugins/squirrly-seo/" -->' . "\n";
        }

        return $header;
    }

    /**
     * Show the Sitemap Header
     *
     * @param array $include Include schema
     */
    public function showSitemapHeader($include = array())
    {
        @ini_set('memory_limit', apply_filters('admin_memory_limit', WP_MAX_MEMORY_LIMIT));

        header('Status: 200 OK', true, 200);
        header('Content-Type: text/xml; charset=' . get_bloginfo('charset'), true);
        //Generate header
        echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . '"?>' . "\n";
        echo apply_filters('sq_sitemap_style', false);

        echo '' . "\n";

        $schema = array(
            'image' => 'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"',
            'video' => 'xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"',
            'news' => 'xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"',
            'mobile' => 'xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0"',
        );

        if(!is_array($include)){
            $include = array();
        }elseif (!empty($include)) {
            $include = array_unique($include);
        }

        switch ($this->sitemap) {
            case 'locations':
                echo '<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
                echo '<Document>' . "\n";
                break;
            case 'sitemap':
                echo '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                    . 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" '
                    . 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
                foreach ($include as $value) {
                    //echo the xmlns for the current sitemap type
                    echo ' ' . $schema[$value] . "\n";
                }
                echo '>' . "\n";
                break;
            case 'sitemap-news':
                array_push($include, 'news');
                $include = array_unique($include);
            default:
                echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                    . 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" '
                    . 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
                if (!empty($include))
                    foreach ($include as $value) {
                        //echo the xmlns for the current sitemap type
                        echo " " . $schema[$value] . " ";
                    }
                echo '>' . "\n";
                break;
        }
    }

    /**
     * Show the Sitemap Footer
     */
    private function showSitemapFooter()
    {
        switch ($this->sitemap) {
            case 'locations':
                echo '</Document>' . "\n";
                echo '</kml>' . "\n";
                break;
            case 'sitemap':
                echo '</sitemapindex>' . "\n";
                break;
            default :
                echo '</urlset>' . "\n";
                break;
        }
    }

    /**
     * Create the XML sitemap
     *
     * @return string
     */
    public function showSitemap()
    {
		do_action('sq_sitemap_xml_before_show');

        switch ($this->sitemap) {
            case 'sitemap':
                $this->showSitemapHeader();
                $sq_sitemap = SQ_Classes_Helpers_Tools::getOption('sq_sitemap');
                $patterns = SQ_Classes_Helpers_Tools::getOption('patterns');

                if (!empty($sq_sitemap))
                    foreach ($sq_sitemap as $name => $value) {

                        //force to show products if not preset
                        if ($name == 'sitemap-product' && !SQ_Classes_Helpers_Tools::isEcommerce()) {
                            continue;
                        }

                        if ($name <> 'sitemap' && ($value[1] == 1 || $value[1] == 2)) {

                            if ($name <> 'sitemap-custom-post' && $name <> 'sitemap-custom-tax' && $name <> 'sitemap-archive') {

	                            //check if available from SEO Automation
	                            $pname = str_replace(array('sitemap-', 'post_'), '', $name);
	                            if (isset($patterns[$pname]['do_sitemap']) && !$patterns[$pname]['do_sitemap']) {
		                            continue;
	                            }

                                echo "\t" . '<sitemap>' . "\n";
                                echo "\t" . '<loc>' . esc_url($this->getXmlUrl($name)) . '</loc>' . "\n";
                                echo "\t" . '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', get_lastpostmodified('gmt'), false) . '</lastmod>' . "\n";
                                echo "\t" . '</sitemap>' . "\n";
                            }

                            if ($name == 'sitemap-post' && $count_posts = wp_count_posts()) {
                                if (isset($count_posts->publish) && (int)$count_posts->publish > 0 && (int)$count_posts->publish > (int)$this->posts_limit) {
                                    $pages = ceil((int)$count_posts->publish / (int)$this->posts_limit);
                                    for ($page = 2; $page <= $pages; $page++) {
                                        echo "\t" . '<sitemap>' . "\n";
                                        echo "\t" . '<loc>' . esc_url($this->getXmlUrl($name, $page)) . '</loc>' . "\n";
                                        echo "\t" . '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', get_lastpostmodified('gmt'), false) . '</lastmod>' . "\n";
                                        echo "\t" . '</sitemap>' . "\n";
                                    }
                                }
                            }
                            if ($name == 'sitemap-page' && $count_posts = wp_count_posts('page')) {
                                if (isset($count_posts->publish) && (int)$count_posts->publish > 0 && (int)$count_posts->publish > (int)$this->posts_limit) {
                                    $pages = ceil((int)$count_posts->publish / (int)$this->posts_limit);
                                    for ($page = 2; $page <= $pages; $page++) {
                                        echo "\t" . '<sitemap>' . "\n";
                                        echo "\t" . '<loc>' . esc_url($this->getXmlUrl($name, $page)) . '</loc>' . "\n";
                                        echo "\t" . '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', get_lastpostmodified('gmt'), false) . '</lastmod>' . "\n";
                                        echo "\t" . '</sitemap>' . "\n";
                                    }
                                }
                            }
                            if ($name == 'sitemap-product' && $count_posts = wp_count_posts('product')) {
                                if (isset($count_posts->publish) && (int)$count_posts->publish > 0 && (int)$count_posts->publish > (int)$this->posts_limit) {
                                    $pages = ceil((int)$count_posts->publish / (int)$this->posts_limit);
                                    for ($page = 2; $page <= $pages; $page++) {
                                        echo "\t" . '<sitemap>' . "\n";
                                        echo "\t" . '<loc>' . esc_url($this->getXmlUrl($name, $page)) . '</loc>' . "\n";
                                        echo "\t" . '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', get_lastpostmodified('gmt'), false) . '</lastmod>' . "\n";
                                        echo "\t" . '</sitemap>' . "\n";
                                    }
                                }
                            }
                            if ($name == 'sitemap-custom-tax'){
                                $taxonomies = get_taxonomies(array('public' => true, '_builtin' => false));
                                foreach (array('nav_menu', 'link_category', 'post_format', 'ngg_tag') as $exclude) {
                                    if (in_array($exclude, $taxonomies)) {
                                        unset($taxonomies[$exclude]);
                                    }
                                }

                                foreach ($taxonomies as $taxonomy) {
                                    if(isset($patterns['tax-' . $taxonomy]['do_sitemap']) && !$patterns['tax-' . $taxonomy]['do_sitemap']){
                                        continue;
                                    }elseif(isset($patterns['custom']['do_sitemap']) && !$patterns['custom']['do_sitemap']){
                                        continue;
                                    }

                                    $count = wp_count_terms( $taxonomy, array('hide_empty'=> true) );
                                    if(!is_wp_error($count) && !empty($count)) {
                                        if ((int)$count > (int)$this->posts_limit) {
                                            $pages = ceil((int)$count / (int)$this->posts_limit);
                                            for ($page = 0; $page < $pages; $page++) {
                                                echo "\t" . '<sitemap>' . "\n";
                                                echo "\t" . '<loc>' . esc_url($this->getXmlUrl($name, $page, false, $taxonomy)) . '</loc>' . "\n";
                                                echo "\t" . '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', get_lastpostmodified('gmt'), false) . '</lastmod>' . "\n";
                                                echo "\t" . '</sitemap>' . "\n";
                                            }
                                        }else {
                                            echo "\t" . '<sitemap>' . "\n";
                                            echo "\t" . '<loc>' . esc_url($this->getXmlUrl($name, 0, false, $taxonomy)) . '</loc>' . "\n";
                                            echo "\t" . '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', get_lastpostmodified('gmt'), false) . '</lastmod>' . "\n";
                                            echo "\t" . '</sitemap>' . "\n";
                                        }
                                    }

                                }
                            }
                            if ($name == 'sitemap-custom-post' ) {
                                $types = get_post_types(array('public' => true));

	                            foreach (array('post', 'page', 'attachment', 'revision', 'nav_menu_item', 'product', 'wpsc-product', 'ngg_tag', 'elementor_library','ct_template','oxy_user_library','fusion_template') as $exclude) {
                                    if (in_array($exclude, $types)) {
                                        unset($types[$exclude]);
                                    }
                                }

                                foreach ($types as $type) {
                                    $type_data = get_post_type_object($type);
                                    if ((isset($type_data->rewrite['publicly_queryable']) && $type_data->rewrite['publicly_queryable'] == 1) || (isset($type_data->publicly_queryable) && $type_data->publicly_queryable == 1)) {
                                        continue;
                                    }
                                    unset($types[$type]);
                                }

                                if (empty($types)) {
                                    array_push($types, 'custom-post');
                                }

                                foreach ($types as $type) {
                                    if(isset($patterns[$type]['do_sitemap']) && !$patterns[$type]['do_sitemap']){
                                        continue;
                                    }

                                    if ($count_posts = wp_count_posts($type)) {

                                        if (isset($count_posts->publish) && (int)$count_posts->publish > 0) {
                                            if ((int)$count_posts->publish > (int)$this->posts_limit) {
                                                $pages = ceil((int)$count_posts->publish / (int)$this->posts_limit);
                                                for ($page = 0; $page < $pages; $page++) {
                                                    echo "\t" . '<sitemap>' . "\n";
                                                    echo "\t" . '<loc>' . esc_url($this->getXmlUrl($name, $page, $type)) . '</loc>' . "\n";
                                                    echo "\t" . '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', get_lastpostmodified('gmt'), false) . '</lastmod>' . "\n";
                                                    echo "\t" . '</sitemap>' . "\n";
                                                }

                                            } else {
                                                echo "\t" . '<sitemap>' . "\n";
                                                echo "\t" . '<loc>' . esc_url($this->getXmlUrl($name, 0, $type)) . '</loc>' . "\n";
                                                echo "\t" . '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', get_lastpostmodified('gmt'), false) . '</lastmod>' . "\n";
                                                echo "\t" . '</sitemap>' . "\n";
                                            }
                                        }
                                    }
                                }

                            }
	                        if ($name == 'sitemap-archive' ) {

		                        if(isset($patterns['archive']['do_sitemap']) && $patterns['archive']['do_sitemap']){
			                        echo "\t" . '<sitemap>' . "\n";
			                        echo "\t" . '<loc>' . esc_url($this->getXmlUrl($name)) . '</loc>' . "\n";
			                        echo "\t" . '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', get_lastpostmodified('gmt'), false) . '</lastmod>' . "\n";
			                        echo "\t" . '</sitemap>' . "\n";
		                        }

		                        $types = get_post_types(array('public' => true, '_builtin' => false));
		                        foreach ($types as $type) {
			                        if (in_array($type, array('elementor_library','ct_template','oxy_user_library','fusion_template'))) continue;

			                        if((isset($patterns['archive-' . $type]['do_sitemap']) && $patterns['archive-' . $type]['do_sitemap'])) {
				                        if (!isset($patterns['archive-' . $type]['doseo']) || $patterns['archive-' . $type]['doseo']) {
					                        echo "\t" . '<sitemap>' . "\n";
					                        echo "\t" . '<loc>' . esc_url($this->getXmlUrl($name, 0, $type)) . '</loc>' . "\n";
					                        echo "\t" . '<lastmod>' . mysql2date('Y-m-d\TH:i:s+00:00', get_lastpostmodified('gmt'), false) . '</lastmod>' . "\n";
					                        echo "\t" . '</sitemap>' . "\n";
				                        }
			                        }

		                        }

	                        }

                        }



                    }

                $this->showSitemapFooter();
                break;
            case 'sitemap-home':
                $this->showPackXml($this->model->getHomeLink());
                break;
            case 'sitemap-news':
                $this->showPackXml($this->model->getListNews());
                break;
            case 'sitemap-category':
            case 'sitemap-post_tag':
            case 'sitemap-custom-tax':
                $this->showPackXml($this->model->getListTerms($this->queryTerms()));
                break;
            case 'sitemap-author':
                $this->showPackXml($this->model->getListAuthors());
                break;
            case 'sitemap-archive':
                $this->showPackXml($this->model->getListArchive());
                break;
            case 'sitemap-attachment':
                $this->showPackXml($this->model->getListAttachments());
                break;
            case 'locations':
                $this->showPackKml($this->model->getKmlXML());
                break;
            default:
                $this->showPackXml($this->model->getListPosts());
                break;
        }

	    do_action('sq_sitemap_xml_after_show');

	    die();
    }

    /**
     * Pach the XML for each sitemap
     *
     * @param  array $xml
     * @return void
     */
    public function showPackXml($xml = array())
    {
        //clear the empty lines
        $xml = array_filter($xml);

        if (empty($xml)) {
            $xml['contains'] = '';
        }
        if (!isset($xml['contains'])) {
            $xml['contains'] = '';
        }
        $this->showSitemapHeader($xml['contains']);

        unset($xml['contains']);
        foreach ($xml as $row) {
            echo "\t" . '<url>' . "\n";

            if (is_array($row)) {
                echo $this->getRecursiveXml($row);
            }
            echo "\t" . '</url>' . "\n";
        }
        $this->showSitemapFooter();
        unset($xml);
    }

    /**
     * Pach the XML for each sitemap
     *
     * @param  array $kml
     * @return void
     */
    public function showPackKml($kml = array())
    {

        $this->showSitemapHeader();
        header('Content-Type: application/vnd.google-earth.kml+xml; charset=' . get_bloginfo('charset'), true);
        echo $this->getRecursiveXml($kml);
        $this->showSitemapFooter();

        unset($kml);
    }

    /**
     * Return XML Structure for the Sitemap
     * @param $xml
     * @param string $pkey
     * @param int $level
     * @return string XML
     */
    public function getRecursiveXml($xml, $pkey = '', $level = 2)
    {
        $str = '';
        $tab = str_repeat("\t", $level);
        if (is_array($xml)) {
            $cnt = 0;
            foreach ($xml as $key => $data) {
                if ($data === false) {
                    $str .= $tab . '<' . $key . '>' . "\n";
                } elseif (!is_array($data) && $data <> '') {
                    $str .= $tab . '<' . $key . ($key == 'video:player_loc' ? ' allow_embed="yes"' : '') . '>' . $data . ((strpos($data, '?') == false && $key == 'video:player_loc') ? '' : '') . '</' . $key . '>' . "\n";
                } else {
                    if ($this->getRecursiveXml($data) <> '') {
                        if (!$this->_ckeckIntergerArray($data)) {
                            $str .= $tab . '<' . (!is_numeric($key) ? $key : $pkey) . '>' . "\n";
                        }
                        $str .= $this->getRecursiveXml($data, $key, ($this->_ckeckIntergerArray($data) ? $level : $level + 1));
                        if (!$this->_ckeckIntergerArray($data)) {
                            $str .= $tab . '</' . (!is_numeric($key) ? $key : $pkey) . '>' . "\n";
                        }
                    }
                }
                $cnt++;
            }
        }
        return $str;
    }

    private function _ckeckIntergerArray($data)
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                return true;
            }
            break;
        }
        return false;
    }

    /**
     * Set the query limit
     *
     * @param  integer $limits
     * @return string
     */
    public function setLimits($limits)
    {
        if (isset($this->posts_limit) && $this->posts_limit > 0) {
            return 'LIMIT 0, ' . $this->posts_limit;
        }

        return '';
    }

    /**
     * Get the url for each sitemap
     *
     * @param string  $sitemap
     * @param integer|false $page
     * @param string|false $type
     * @return string
     */
    public function getXmlUrl($sitemap, $page = false, $type = false, $taxonomy = false)
    {
        $sq_sitemap = SQ_Classes_Helpers_Tools::getOption('sq_sitemap');

        if (!get_option('permalink_structure')) {
            $sitemap = '?sq_feed=' . str_replace('.xml', '', $sitemap);
            $sitemap .= (($page && (int)$page > 0) ? '&amp;page=' . $page : '');
            $sitemap .= (($type && $type <> '') ? '&amp;type=' . $type : '');
            $sitemap .= (($taxonomy && $taxonomy <> '') ? '&amp;taxonomy=' . $taxonomy : '');
        } else {
            if (isset($sq_sitemap[$sitemap])) {
                $sitemap = $sq_sitemap[$sitemap][0];
                $sitemap .= (($page && (int)$page > 0) ? '?page=' . (int)$page : '');
                $sitemap .= (($type && $type <> '') ? (strpos($sitemap,'?') !== false ? '&amp;' : '?') . 'type=' . $type : '');
                $sitemap .= (($taxonomy && $taxonomy <> '') ? (strpos($sitemap,'?') !== false ? '&amp;' : '?') . 'taxonomy=' . $taxonomy : '');
            }

            if (strpos($sitemap, '.xml') === false) {
                $sitemap .= '.xml';
            }
        }

        if ($this->model->language <> '') {
            if(function_exists('pll_home_url')) {
                return pll_home_url($this->model->language) . $sitemap;
            }elseif (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] <> '') {
                if(strpos($this->model->language, '_') !== false) {
                    $language = substr($this->model->language, 0, strpos($this->model->language, '_'));
                    if(preg_match("/\/$language\//", $_SERVER['REQUEST_URI']) && !preg_match("/\/$language\//", trailingslashit(home_url()))) {
                        return esc_url(trailingslashit(home_url())) . $language . '/' . $sitemap;
                    }
                }
            }
        }

        return esc_url(trailingslashit(home_url())) . $sitemap;
    }

    public function getKmlUrl($sitemap, $page = null)
    {
        $sq_sitemap = SQ_Classes_Helpers_Tools::getOption('sq_sitemap');

        if (!get_option('permalink_structure')) {
            $sitemap = '?sq_feed=' . str_replace('.kml', '', $sitemap) . (isset($page) ? '&amp;page=' . $page : '');
        } else {
            if (isset($sq_sitemap[$sitemap])) {
                $sitemap = $sq_sitemap[$sitemap][0] . (isset($page) ? '?page=' . $page : '');
            }

            if (strpos($sitemap, '.kml') === false) {
                $sitemap .= '.kml';
            }
        }

        return esc_url(trailingslashit(home_url())) . $sitemap;
    }


    /**
     * Process the on-time cron if called
     */
    public function processCron()
    {
        SQ_Classes_ObjController::getClass('SQ_Classes_Helpers_Tools');

        $sq_sitemap = SQ_Classes_Helpers_Tools::getOption('sq_sitemap');
        if (!empty($sq_sitemap)) {
            foreach ($sq_sitemap as $name => $sitemap) {
                if ($sitemap[1] == 1) { //is the default sitemap
                    $this->SendPing($this->getXmlUrl($name));
                }
            }
        }
    }

    /**
     * Ping the sitemap to Google and Bing
     *
     * @param  string $sitemapUrl
     * @return boolean
     */
    protected function SendPing($sitemapUrl)
    {
        $success = true;
        $urls = array(
            "https://www.google.com/ping?sitemap=%s",
        );

        $options = array(
            'method' => 'get',
            'sslverify' => false,
            'timeout' => 10
        );

        foreach ($urls as $url) {
            if (!SQ_Classes_ObjController::getClass('SQ_Classes_RemoteController')->sq_wpcall(sprintf($url, $sitemapUrl), $options)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Delete the fizical file if exists
     *
     * @return boolean
     */
    public function deleteSitemapFile()
    {
        $sq_sitemap = SQ_Classes_Helpers_Tools::getOption('sq_sitemap');
        if (isset($sq_sitemap[$this->root])) {
            if (file_exists(ABSPATH . $sq_sitemap[$this->root])) {
                @unlink(ABSPATH . $sq_sitemap[$this->root]);
                return true;
            }
        }
        return false;
    }

    /**
     * Remove the trailing slash from permalinks that have an extension,
     * such as /sitemap.xml
     *
     * @param  $request
     * @return string
     */
    public function untrailingslashit($request)
    {
        if (pathinfo($request, PATHINFO_EXTENSION)) {
            return untrailingslashit($request);
        }
        return $request; // trailingslashit($request);
    }

    public function postFilter(&$query)
    {
        $query->set('tax_query', array());
    }

    /**
     * Filter the Custom Taxonomy
     *
     * @param  $query
     * @param  $args
     * @return array
     */
    public function customTaxFilter($query, $args)
    {
        global $wpdb;
        $query[] = $wpdb->prepare(
            "(SELECT UNIX_TIMESTAMP(MAX(p.post_date_gmt)) as _mod_date
                 FROM `$wpdb->posts` p, `$wpdb->term_relationships` r
                 WHERE p.ID = r.object_id  
                 AND p.post_status = %s  
                 AND p.post_password = ''  
                 AND r.term_taxonomy_id = tt.term_taxonomy_id 
                ) as lastmod", 'publish'
        );


        return $query;
    }

    public function pageFilter(&$query)
    {
        $query->set('post_type', array('page'));
        $query->set('tax_query', array());
    }

    public function authorFilter()
    {
        //get only the author with posts
        add_filter('pre_user_query', array($this, 'userFilter'));
        return get_users();
    }

    public function userFilter($query)
    {
        global $wpdb;

        $query->query_fields .= ',p.lastmod';
        $query->query_from .= ' LEFT OUTER JOIN (
            SELECT MAX(post_modified) as lastmod, post_author, COUNT(*) as post_count
            FROM `' . $wpdb->posts . '`
            WHERE post_type = "post" AND post_status = "publish"
            GROUP BY post_author
        ) p ON (wp_users.ID = p.post_author)';
        $query->query_where .= ' AND post_count  > 0 ';
    }

    public function customPostFilter(&$query)
    {
        $types = get_post_types(array('public' => true));
        foreach (array('post', 'page', 'attachment', 'revision', 'nav_menu_item', 'product', 'wpsc-product') as $exclude) {
            if (in_array($exclude, $types)) {
                unset($types[$exclude]);
            }
        }

        foreach ($types as $type) {
            $type_data = get_post_type_object($type);
            if ((isset($type_data->rewrite['feeds']) && $type_data->rewrite['feeds'] == 1) || (isset($type_data->feeds) && $type_data->feeds == 1)) {
                continue;
            }
            unset($types[$type]);
        }

        if (empty($types)) {
            array_push($types, 'custom-post');
        }

        $query->set('post_type', $types); // id of page or post
        $query->set('tax_query', array());
    }

    public function productFilter(&$query)
    {

        if (!$types = SQ_Classes_Helpers_Tools::isEcommerce()) {
            $types = array('custom-post');
        }
        $query->set('post_type', $types); // id of page or post
        $query->set('tax_query', array());
    }

    public function archiveFilter()
    {
        global $wpdb, $sq_query;

        if(!empty($sq_query['post_type'])){
            foreach ($sq_query['post_type'] as $type){
                $archives[$type] = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT DISTINCT YEAR(post_date_gmt) as `year`, MONTH(post_date_gmt) as `month`, max(post_date_gmt) as lastmod, count(ID) as posts
                        FROM `".$wpdb->posts."`
                        WHERE post_date_gmt < NOW()  AND post_status = %s  AND post_type = %s
                        GROUP BY YEAR(post_date_gmt),  MONTH(post_date_gmt)
                        ORDER BY  post_date_gmt DESC", 'publish', $type
                    )
                );
            }
        }
        return $archives;
    }

    /**
     *
     * @return array of terms
     */
    public function queryTerms(){
        global $sq_query;

        if(isset($sq_query['taxonomy']) && !empty($sq_query['taxonomy'])) {
            return get_terms($sq_query['taxonomy'], $sq_query);
        }

        return array();
    }

}
