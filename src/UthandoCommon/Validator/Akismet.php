<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   UthandoCommon\Validator
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace UthandoCommon\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\ValidatorPluginManager;
use ZendService\Akismet\Akismet as AkismetService;
use ZendService\Akismet\Exception;

/**
 * Class Akismet
 *
 * @package UthandoCommon\Validator
 * @method ValidatorPluginManager getServiceLocator()
 */
class Akismet extends AbstractValidator
{
    const INVALID = 'invalid';
    const SPAM = 'isSpam';

    protected $messageTemplates = [
        self::INVALID => 'Invalid input',
        self::SPAM => 'The text seems to be spam',
    ];

    /**
     * Akismet API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The front page or home URL of the instance making the request. For a blog or wiki this would be the front page.
     * Note: Must be a full URI, including http://
     *
     * @var string
     */
    protected $blog;

    /**
     * Name submitted with the comment.
     *
     * @var string
     */
    protected $commentAuthor;

    /**
     * Email address submitted with the comment.
     *
     * @var string
     */
    protected $commentAuthorEmail;

    /**
     * May be blank, comment, trackback, pingback, or a made up value like "registration".
     *
     * @var string
     */
    protected $commentType;

    /**
     * User agent string of the web browser submitting the comment - typically the HTTP_USER_AGENT cgi variable.
     * Not to be confused with the user agent of your Akismet library.
     *
     * @var string
     */
    protected $userAgent;

    /**
     * IP address of the comment submitter.
     *
     * @var string
     */
    protected $userIp;

    public function __construct($options = [])
    {
        if (array_key_exists('api_key', $options)) {
            $this->setApiKey($options['api_key']);
        }

        if (array_key_exists('blog', $options)) {
            $this->setBlog($options['blog']);
        }

        if (array_key_exists('comment_author', $options)) {
            $this->setCommentAuthor($options['comment_author']);
        }

        if (array_key_exists('comment_author_email', $options)) {
            $this->setCommentAuthorEmail($options['comment_author_email']);
        }

        if (array_key_exists('comment_type', $options)) {
            $this->setCommentType($options['comment_type']);
        }

        if (array_key_exists('user_agent', $options)) {
            $this->setUserAgent($options['user_agent']);
        }

        if (array_key_exists('user_ip', $options)) {
            $this->setUserIp($options['user_ip']);
        }

        parent::__construct($options);
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        if (empty($apiKey)) {
            throw new Exception\InvalidArgumentException('API key cannot be empty');
        }

        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @param $blog
     * @return $this
     */
    public function setBlog($blog)
    {
        if (empty($blog)) {
            throw new Exception\InvalidArgumentException('The url cannot be empty');
        }

        $this->blog = $blog;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserIp()
    {
        return $this->userIp;
    }

    /**
     * @param string $userIp
     * @return $this
     */
    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentAuthor()
    {
        return $this->commentAuthor;
    }

    /**
     * @param string $commentAuthor
     * @return $this
     */
    public function setCommentAuthor($commentAuthor)
    {
        $this->commentAuthor = $commentAuthor;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentAuthorEmail()
    {
        return $this->commentAuthorEmail;
    }

    /**
     * @param string $commentAuthorEmail
     * @return $this
     */
    public function setCommentAuthorEmail($commentAuthorEmail)
    {
        $this->commentAuthorEmail = $commentAuthorEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentType()
    {
        return $this->commentType;
    }

    /**
     * @param string $commentType
     * @return $this
     */
    public function setCommentType($commentType)
    {
        $this->commentType = $commentType;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * Validate the value so see if it's spam.
     *
     * @param string $value
     * @param null $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if (!is_array($value)) {
            $this->error(self::INVALID);
            return false;
        }

        $this->setValue($value);

        $akismet = new AkismetService($this->getApiKey(), $this->getBlog());

        if (!$akismet->verifyKey($this->getApiKey())) {
            throw new Exception\InvalidArgumentException('Invalid API key for Akismet');
        }

        $data = [
            'comment_type' => $this->getCommentType(),
            'comment_author' => $context[$this->getCommentAuthor()],
            'comment_author_email' => $context[$this->getCommentAuthorEmail()],
            'comment_content' => $value,
            'user_agent' => $this->getUserAgent(),
            'user_ip' => $this->getUserIp(),
        ];

        if ($akismet->isSpam($data)) {
            $this->error(self::SPAM);
            return false;
        }

        return true;
    }
}
