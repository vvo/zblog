<?php

// We directly load the functions we are often going to use in the
// views. This makes the code cleaner.
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

class Zblog_Blog {

	private $nb_posts_per_page = 3;

//	private $disqus_api_key = 'oygAMeE83ouctpsnBkQS7cvZXNRPtPPFxp1WdIQHCe1rjCj3obPD4YhcZSNpQsJE';
//	private $disqus_forum = 376164; // id of the zeroload website in disqus

	// from disqus website, can count on multiple threads at a time, try it !!
//	public function get_comment_count($post) {
//		$disqus = new Zblog_Disqus($this->disqus_api_key);
//		$disqus->setForumKey($this->disqus_forum);
//		$disqus->setThreadByIdentifier('BLOGPOSTDEV-'.$post->id, $post->title);
//		return $disqus->getNumPosts();
//	}

//	public function get_comments($post) {
//		$disqus = new Zblog_Disqus($this->disqus_api_key);
//		$disqus->setForumKey($this->disqus_forum);
//		$disqus->setThreadByIdentifier('BLOGPOSTDEV-'.$post->id, $post->title);
//		return 	$disqus->getPosts();
//	}

	// next prev arrows show next prev post (no day/month indication in the url)
	public function view_post($request, $match) {
		$db = Pluf::db();

		$post = Pluf::factory('Zblog_Post')
				->getOne(array('filter' => array('draft=0', 'title='.$db->esc($match[1]))));

		// no post or url has upper characters and we do not want this
		if ($post === null || strtolower($post->title) !== $match[1]) {
			throw new Pluf_HTTP_Error404();
		}

		$prev_post = Pluf::factory('Zblog_Post')
				->getOne(
				array('nb' => 1,
				'filter' => array('draft=0', 'post_date < \''.$post->post_date.'\''),
				'order' => 'post_date DESC'));

		$now = gmdate('Y-m-d H:i:s');

		$next_post = Pluf::factory('Zblog_Post')
				->getOne(
				array('nb' => 1,
				'filter' => array(
						'draft=0',
						'post_date > \''.$post->post_date.'\'',
						'post_date < \''.$now.'\''
				),
				'order' => 'post_date'));

		$prev_link = null;
		$next_link = null;

		// Z for GMT
		if($prev_post != null) {
			$prev_link = array("text" => $prev_post->title,
					"href" => Pluf_HTTP_URL_urlForView('blog_post', rawurlencode(strtolower($prev_post->title))));
		}

		if($next_post != null) {
			$next_link = array("text" => $next_post->title,
					"href" => Pluf_HTTP_URL_urlForView('blog_post', rawurlencode(strtolower($next_post->title))));
		}

		// control if post exists then 404
		$post->tags = $post->get_tags_list();

//		$post->nbComments = $this->get_comment_count($post);
//		$post->comments = $this->get_comments($post);
//
//		var_dump($post->nbComments);
//		var_dump($post->comments);
//		exit;

		$time = strtotime($post->post_date.'Z');
		$year = date('Y', $time);
		$month = date('m', $time);
		$day = date('d', $time);

		$context = new Pluf_Template_Context(array(
						'page_title' => $post->title." - ".Pluf_Date::gmDateToString($post->post_date, "%d/%m/%Y"),
						'post' => $post,
						'prev_link' => (object) $prev_link,
						'next_link' => (object) $next_link,
						'last_posts' => $this->get_last_posts(),
						'calendar' => $this->get_calendar($year, $month, $day))
		);

		$tmpl = new Pluf_Template('zblog/blog/view-post.html');
		return new Pluf_HTTP_Response($tmpl->render($context));
	}

	/*	public function view_year($request, $match) {
		$post = Pluf_Shortcuts_GetObjectOr404('Zblog_Post', $match[1]);

		return Pluf_Shortcuts_RenderToResponse('zblog/blog/view-posts.html',
				array('page_title' => $post->title,
				'post' => $post));
	}*/

	// show all posts for a month like a ordered list with days, prev / next arrows = next month prev month
	public function view_month($request, $match) {
		$year = (int)$match[1];
		$month = (int)$match[2];

		// control date format + no future pages
		if (!checkdate($month, 1, $year) ||	mktime(0, 0, 0, $month, 1, $year) > time()) {
			throw new Pluf_HTTP_Error404();
		}

		// control range of months / year

		$last_day_in_month = date('t', strtotime($year.'-'.$month));
		$start_month = gmdate('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year));
		$current_month = date('m');
		$current_year = date('Y');
		if ($current_month == $month && $current_year == $year) {
			$end_month = gmdate('Y-m-d H:i:s');
		} else {
			$end_month = gmdate('Y-m-d H:i:s', mktime(23, 59, 59, $month, $last_day_in_month, $year));
		}

		$posts = Pluf::factory('Zblog_Post')
				->getList(array(
				'filter' => array('draft=0', 'post_date BETWEEN \''.$start_month.'\' AND \''.$end_month.'\''),
				'order' => array('post_date DESC')));

		if ($posts[0] === null) {
			throw new Pluf_HTTP_Error404();
		}

		$month_name = strftime("%B", mktime(0, 0, 0, $month, 1));

		$date_first_post_of_month = $posts[count($posts)-1]->post_date;
		$date_last_post_of_month = $posts[0]->post_date;

		$prev_month_last_post = null;
		$next_month_first_post = null;

		$prev_month_last_post = Pluf::factory('Zblog_Post')
				->getOne(
				array('nb' => 1,
				'filter' => array('draft=0', 'post_date < \''.$date_first_post_of_month.'\''),
				'order' => 'post_date DESC'));

		if (!($current_month == $month && $current_year == $year)) {
			$next_month_first_post = Pluf::factory('Zblog_Post')
					->getOne(array('nb' => 1,
					'filter' => array('draft=0', 'post_date > \''.$date_last_post_of_month.'\''),
					'order' => array('post_date')));
		}

		$prev_link = null;
		$next_link = null;

		// Z for GMT
		if($prev_month_last_post != null) {
			$prev_month = strtotime($prev_month_last_post->post_date.'Z');
			$prev_link = array("text" => strftime('%B', $prev_month),
					"href" => Pluf_HTTP_URL_urlForView('blog_month', array($year, date("m", $prev_month))));
		}

		if($next_month_first_post != null) {
			$next_month = strtotime($next_month_first_post->post_date.'Z');
			$next_link = array("text" => strftime('%B', $next_month),
					"href" => Pluf_HTTP_URL_urlForView('blog_month', array($year, date("m", $next_month))));
		}

		$calendar = $this->get_calendar($year, $month);

		$context = new Pluf_Template_Context(array('page_title' => 'Articles pour '.$month_name.' '.$year,
						'last_posts' => $this->get_last_posts(),
						'posts' => $posts,
						'prev_link' => (object) $prev_link,
						'next_link' => (object) $next_link,
						'month_name'=> $month_name,
						'year' => $year,
						'month' => $month,
						'calendar' => $calendar)
		);

		$tmpl = new Pluf_Template('zblog/blog/view-archive.html');
		return new Pluf_HTTP_Response($tmpl->render($context));
	}

	// show posts for the day, next prev arrow go to next prev day that have posts (can be another month)
	public function view_day($request, $match) {
		$year = (int)$match[1];
		$month = (int)$match[2];
		$day = (int)$match[3];

		// control date format + no future pages
		if (!checkdate($month, $day, $year) || mktime(0, 0, 0, $month, $day, $year) > time() ) {
			throw new Pluf_HTTP_Error404();
		}

		$start_day = gmdate('Y-m-d H:i:s', strtotime($year.'-'.$month.'-'.$day.' 00:00:00'));

		$current_day = date('d');
		$current_month = date('m');
		$current_year = date('Y');
		if ($current_month == $month && $current_year == $year && $current_day == $day) {
			$end_day = gmdate('Y-m-d H:i:s');
		} else {
			$end_day= gmdate('Y-m-d H:i:s', strtotime($year.'-'.$month.'-'.$day.' 23:59:59'));
		}

		$posts = Pluf::factory('Zblog_Post')
				->getList(array(
				'filter' => array('draft=0', 'post_date BETWEEN \''.$start_day.'\' AND \''.$end_day.'\''),
				'order' => array('post_date DESC')));

		// can be the same, most of the times i guess
		$date_first_post_of_day = $posts[count($posts)-1]->post_date;
		$date_last_post_of_day = $posts[0]->post_date;

		$prev_day_last_post = Pluf::factory('Zblog_Post')
				->getOne(
				array('nb' => 1,
				'filter' => array('draft=0', 'post_date < \''.$date_first_post_of_day.'\''),
				'order' => 'post_date DESC'));

		$next_day_first_post = Pluf::factory('Zblog_Post')
				->getOne(array('nb' => 1,
				'filter' => array(
						'draft=0',
						'post_date > \''.$date_last_post_of_day.'\'',
						'post_date < \''.$now.'\''),
				'order' => array('post_date')));

		$prev_link = null;
		$next_link = null;

		// Z for GMT
		if($prev_day_last_post != null) {
			$prev_day = strtotime($prev_day_last_post->post_date.'Z');
			$prev_link = array("text" => strftime('%e %B %Y', $prev_day),
					"href" => Pluf_HTTP_URL_urlForView('blog_day', array($year, date("m", $prev_day), date('d', $prev_day))));
		}

		if($next_day_first_post != null) {
			$next_day = strtotime($next_day_first_post->post_date.'Z');
			$next_link = array("text" => strftime('%e %B %Y', $next_day),
					"href" => Pluf_HTTP_URL_urlForView('blog_day', array($year, date("m", $next_day), date('d', $next_day))));
		}

		return $this->get_classic_page(
				$posts,
				$this->get_calendar($year, $month, $day),
				(object) $prev_link,
				(object) $next_link,
				'Articles du '.str_pad($day, 2, '0', STR_PAD_LEFT).'/'.str_pad($month, 2, '0', STR_PAD_LEFT).'/'.$year,
				true);

	}

	public function view_by_tag($request, $match) {
		$db = Pluf::db();

		$prev_link = array("text" => "articles précédents");
		$next_link = array("text" => "articles suivants");

		$tag = Pluf::factory('Zblog_Tag')->getOne(array('filter' => array('name='.$db->esc($match[1]))));

		// no tag or tag name in uppercase
		if ($tag == null || strtolower($tag->name) !== $match[1]) {
			throw new Pluf_HTTP_Error404();
		}

		$now = gmdate('Y-m-d H:i:s');

		$total_posts_available = $tag->get_zblog_post_list(
				array('count' => true,
				'filter' => array('draft=0', 'post_date < \''.$now.'\''))
		);
		$total_posts_available = (int)$total_posts_available[0]['nb_items'];

		if ($total_posts_available < 1) {
			throw new Pluf_HTTP_Error404();
		}

		$num_page = (int)$match[2];

		$last_post_for_page = $match[2]*$this->nb_posts_per_page+$this->nb_posts_per_page;

		if ($num_page > 0) {
			// specific page asked
			// do not archive theses pages in google, modify robots.txt
			$start_at = $this->nb_posts_per_page*$num_page;

			if($num_page == 1) {
				$next_link["href"] = Pluf_HTTP_URL_urlForView('blog_tag', array($match[1]));
			} else {
				$next_link["href"] = Pluf_HTTP_URL_urlForView('blog_tag_page', array($match[1], $num_page-1));
			}

			if($last_post_for_page < $total_posts_available) {
				$prev_link["href"] = Pluf_HTTP_URL_urlForView('blog_tag_page', array($match[1], $num_page+1));
			} else {
				$prev_link["href"] = null; // last page
			}
		} else {
			$start_at = 0; // homepage
			if($last_post_for_page < $total_posts_available) {
				$prev_link["href"] = Pluf_HTTP_URL_urlForView('blog_tag_page', array($match[1], 1));
			} else {
				$prev_link["href"] = null;
			}

			$next_link["href"] = null;
		}

		$posts = $tag->get_zblog_post_list(
				array('nb' => $this->nb_posts_per_page, 'start' => $start_at,
				'filter' => array('draft=0', 'post_date < \''.$now.'\''),
				'order' => array('post_date DESC')));

		if ($posts[0] === null) {
			throw new Pluf_HTTP_Error404();
		}

		return $this->get_classic_page(
				$posts,
				$this->get_calendar_for_now(),
				(object) $prev_link,
				(object) $next_link,
				'Billets sur le tag : '.$tag->name,
				true);
	}

	/* this is the homepage */
	public function blog($request, $match) {
		$prev_link = array("text" => "articles précédents");
		$next_link = array("text" => "articles suivants");

		$total_posts_available = Pluf::factory('Zblog_Post')
				->getCount(
				array('filter' => array('draft=0', 'post_date < \''.gmdate('Y-m-d H:i:s').'\''))
		);

		$num_page = (int)$match[1];

		$last_post_for_page = $num_page*$this->nb_posts_per_page+$this->nb_posts_per_page;

		if ($num_page > 0) {
			// specific page asked
			// do not archive theses pages in google, modify robots.txt
			$start_at = $this->nb_posts_per_page*$num_page;

			if($num_page == 1) {
				$next_link["href"] = Pluf_HTTP_URL_urlForView('blog_homepage');
			} else {
				$next_link["href"] = Pluf_HTTP_URL_urlForView('blog_page', array($num_page-1));
			}

			if($last_post_for_page < $total_posts_available) {
				$prev_link["href"] = Pluf_HTTP_URL_urlForView('blog_page', array($num_page+1));
			} else {
				$prev_link["href"] = null; // last page
			}
		} else {
			$start_at = 0; // homepage
			if($last_post_for_page < $total_posts_available) {
				$prev_link["href"] = Pluf_HTTP_URL_urlForView('blog_page', array(1));
			} else {
				$prev_link["href"] = null; // last page
			}
			$next_link["href"] = null;
		}

		$posts = Pluf::factory('Zblog_Post')
				->getList(array('nb' => $this->nb_posts_per_page,
				'start' => $start_at,
				'filter' => array('draft=0', 'post_date < \''.gmdate('Y-m-d H:i:s').'\''),
				'order' => array('post_date DESC')));

		if ($posts[0] === null) {
			throw new Pluf_HTTP_Error404();
		}

		// control if there's posts then 404
		return $this->get_classic_page(
				$posts,
				$this->get_calendar_for_now(),
				(object) $prev_link,
				(object) $next_link);
	}

	public function get_classic_page($posts, $calendar, $prev_link=null, $next_link=null, 
			$title = 'blog performance web & développement front-end', $show_title_in_page = false) {

		$tags = array();
		foreach($posts as $post) {
			$post->tags = $post->get_tags_list();
		}

		$context = new Pluf_Template_Context(array('page_title' => $title,
						'last_posts' => $this->get_last_posts(),
						'posts' => $posts,
						'prev_link' => $prev_link,
						'next_link' => $next_link,
						'calendar' => $calendar,
						'show_title' => $show_title_in_page)
		);

		$tmpl = new Pluf_Template('zblog/blog/view-posts.html');
		return new Pluf_HTTP_Response($tmpl->render($context));
	}

	public function get_calendar_for_now() {
		return $this->get_calendar(date('Y'), date('m'), date('d'));
	}

	public function get_calendar($year = null, $month = null, $day = null) {

		$nb_days = date('t', mktime(0, 0, 0, $month, 1, $year));

		$start_year = gmdate('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, $year));

		$current_month = date('m');

		$current_year = date('Y');

		// "end year" since i want to be able to pre-posts articles when on vacation,
		// just do not show them in the calendar
		// so "end of year" is always NOW !
		$end_year = gmdate('Y-m-d H:i:s');

		// same thing for end of month, if we are the 3 of june, this is the end of month for query
		$start_month = gmdate('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year));
		if ((int)$current_month === (int)$month && (int)$current_year === (int)$year) {
			$end_month = gmdate('Y-m-d H:i:s');
		} else {
			$end_month = gmdate('Y-m-d H:i:s', mktime(23, 59, 59, $month, $nb_days, $year));
		}

		if ((int)$month === (int)$current_month && (int)$year === (int)$current_year) {
			// either it's the homepage, or the current-month-year-page
			$current_day = date('d');
		} else {
			$current_day = null;
		}

		// get posts count per year per month for the asked year ['2010-02' => 5, '2010-05' => 2]
		$posts_per_year_month = Pluf::factory('Zblog_Post')->getList(
				array(
				'filter' => array('draft=0', 'post_date BETWEEN \''.$start_year.'\' AND \''.$end_year.'\'')
				)
		);

		// get posts count for the asked month
		$posts_per_year_month_day = Pluf::factory('Zblog_Post')->getList(
				array(
				'filter' => array('draft=0', 'post_date BETWEEN \''.$start_month.'\' AND \''.$end_month.'\'')
				)
		);

		// number of posts per days and months construction
		// could be done in one loop

		// months construction with hint if there's posts for a particular month
		$months = array();

		foreach ($posts_per_year_month as $post) {
			$month_id = (int)Pluf_Date::gmDateToString($post->post_date, "%m");
			if(!isset($months[$month_id])) {
				$months[$month_id] = new stdClass();
			}
			$months[$month_id]->nb_posts++;
		}

		// complete the months array with month names
		for ($i=1; $i <= 12; $i++) {
			$months[$i]->name = strftime('%B', mktime(0, 0, 0, $i, 1));
		}

		// days construction with hint if there's a post for a particular day
		$days = array();

		foreach($posts_per_year_month_day as $post) {
			$day_id = (int)Pluf_Date::gmDateToString($post->post_date, "%d");
			if(!isset($days[$day_id])) {
				$days[$day_id] = new stdClass();
			}
			$days[$day_id]->nb_posts++;
		}

		// complete day array
		for ($i=1; $i <= $nb_days; $i++) {
			if (!isset($days[$i])) {
				$days[$i] = null;
			}
		}

		// reorder array keys, so that january comes before februar etc..
		ksort($months);
		ksort($days);

		$context = new Pluf_Template_Context(
				array(
						'A_month' => str_pad($month, 2, '0', STR_PAD_LEFT),
						'nb_days' => $nb_days,
						'A_year' => $year,
						'months' => $months,
						'A_day' => str_pad($day, 2, '0', STR_PAD_LEFT),
						'days' => $days,
						'current_day' => $current_day,
						'current_month' => $current_month
				)
		);

		$tmpl = new Pluf_Template('zblog/blog/calendar.html');

		return $tmpl->render($context);
	}

	public function get_last_posts() {
		return Pluf::factory('Zblog_Post')->getList(
				array(
				'filter' => array('draft=0', 'post_date < \''.gmdate('Y-m-d H:i:s').'\''),
					'order' => array('post_date DESC'), 'nb' => 5)
		);
	}

	public function atom_feed() {
		$posts = Pluf::factory('Zblog_Post')->getList(
				array(
				'filter' => array('draft=0', 'post_date < \''.gmdate('Y-m-d H:i:s').'\''),
					'order' => array('post_date DESC'))
		);

		$now = gmdate(DateTime::ATOM);

		foreach($posts as $post) {
			$post->tags = $post->get_tags_list();
			$post->atom_date = gmdate(DateTime::ATOM, strtotime($post->post_date.'Z'));
		}

		$context = new Pluf_Template_Context(
				array(
						'posts' => $posts,
						'now' => $now
				)
		);

		$tmpl = new Pluf_Template('zblog/blog/atom.xml');

		header('Content-type: application/atom+xml; charset=utf-8');
		echo '<?xml version="1.0" encoding="utf-8"?>';
		echo "\n";
		echo $tmpl->render($context);
		exit;

/*		
		return new Pluf_HTTP_Response('<?xml version="1.0" encoding="utf-8"?>'.);*/
		
	}
}
