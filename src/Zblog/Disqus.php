<?php
class Zblog_Disqus
{
    /**
     * Constants
     */
    const API_HOST = 'http://disqus.com/api/';

    /*========================================================================*/
    /* VARIABLES                                                              */
    /*========================================================================*/
    /**
     * User API key
     *
     * @var string
     */
    protected $_userKey;

    /**
     * Forum API key
     *
     * @var string
     */
    protected $_forumKey;

	/**
	 *
	 * @var string
	 */
	protected $_api_version;

    /**
     * Thread key
     *
     * @var int
     */
    protected $_threadKey;

    /*========================================================================*/
    /* CONSTRUCTOR                                                            */
    /*========================================================================*/
    /**
     * Constructor
     *
     * @param   string  $userKey    The user api key
     */
    public function __construct($userKey)
    {
        $this->_userKey = $userKey;
        $this->_forumKey = '';
        $this->_threadKey = 0;
		$this->_api_version = '1.1';
    }

    /*========================================================================*/
    /* PUBLIC METHODS                                                         */
    /*========================================================================*/
    /**
     * Get the forum list
     *
     * @access  public
     * @return  array    The forum list
     */
    public function getForumList()
    {
        return $this->_getResponse('get_forum_list');
    }

    /**
     * Set the forum API key
     *
     * @access  public
     * @param   string  $id     The forum api key
     * @return  Disqus          The class instance
     */
    public function setForumKey($id)
    {
        $response = $this->_getResponse('get_forum_api_key', 'user', array('forum_id' => $id));
        $this->_forumKey = $response;
        return $this;
    }

    /**
     * Set the thread key
     *
     * @access  public
     * @param   int     $key    The thread key
     * @return  Disqus          The class instance
     */
    public function setThreadKey($key)
    {
        $this->_threadKey = $key;
        return $this;
    }

    /**
     * Set the thread key with an identifier
     *
     * @access  public
     * @param   string  $id     The thread identifier
     * @return  Disqus          The class instance
     */
    public function setThreadByIdentifier($id, $title = null)
    {
        $response = $this->getThreadByIdentifier($id, $title);
        $this->_threadKey = $response['thread']['id'];
        return $this;
    }

    /**
     * Get the thread list
     *
     * @access  public
     * @return  array           The thread list
     */
    public function getThreadList()
    {
        return $this->_getResponse('get_thread_list', 'forum');
    }

    /**
     * Get the thread object.
     * If the thread doesn't exist, it will be created
     *
     * @access  public
     * @param   string  $id     The thread identifier
     * @param   string  $title  The thread title
     * @return  array
     */
    public function getThreadByIdentifier($id, $title = null)
    {
        if ($title == null) {
            $title = $id;
        }
        return $this->_getResponse('thread_by_identifier', 'forum', array('identifier' => $id, 'title' => $title), 'post');
    }

	/**
	 * Number of comments in an article
	 * @return int
	 */
	public function getNumPosts()
	{
		return $this->_getResponse('get_num_posts', 'user', array('thread_ids' => $this->_threadKey));
	}

    /**
     * Get thread posts
     *
     * @access  public
     * @return  array           The post list
     */
    public function getPosts()
    {
        return $this->_getResponse('get_thread_posts', 'user', array('thread_id' => $this->_threadKey));
    }

    /**
     * Create a post
     *
     * @access  public
     * @param   string  $author_name    The author name
     * @param   string  $author_email   The author email
     * @param   string  $message        The message
     * @return  array                   The response
     */
    public function createPost($author_name, $author_email, $message)
    {
        $author_name = trim($author_name);
        if (empty($author_name)) {
            $author_name = 'anonymous';
        }
        $data = array(
            'thread_id'     => $this->_threadKey,
            'author_name'   => $author_name,
            'author_email'  => $author_email,
            'message'       => $message,
			'ip_address'	=> $_SERVER['REMOTE_ADDR']
        );
        return $this->_getResponse('create_post', 'forum', $data, 'post');
    }

    /*========================================================================*/
    /* PRIVATE METHODS                                                        */
    /*========================================================================*/
    /**
     * Send a request to the API and get the response
     *
     * @access  private
     * @param   string  $method     The method of the API
     * @param   string  $keyType    The type of the request ('user' or 'forum')
     * @param   array   $params     The parameters
     * @param   string  $httpMethod The HTTP method ('get' or 'post')
     * @return  array|bool          The response array or false if it fails
     */
    protected function _getResponse($method, $keyType = 'user', $params = array(), $httpMethod = 'get')
    {
        switch ($keyType) {
            case 'forum':
                $params['forum_api_key'] = $this->_forumKey;
                break;
            default:
            case 'user':
                $params['user_api_key'] = $this->_userKey;
        }

		$params['api_version'] = $this->_api_version;

        $queryString = http_build_query($params);

        if ($httpMethod == 'post') {
            $context = stream_context_create(array('http' => array(
                'method'    => 'POST',
                'timeout'   => 1,
                'header'    => "Content-type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($queryString) . "\r\n",
                'content'   => $queryString
            )));

            $response = @file_get_contents(self::API_HOST . $method . '/', false, $context);
        } else {

            $context = stream_context_create(array('http' => array(
                'method'    => 'GET',
                'timeout'   => 1,
            )));


            $response = @file_get_contents(self::API_HOST . $method . '/?' . $queryString, false, $context);
        }

        // If error
        if ($response === false) {
            return false;
        }

        // Decode the response
        $data = json_decode($response, true);
        if (array_key_exists('succeeded', $data) && $data['succeeded']) {
            return $data['message'];
        }
        return false;
    }
}