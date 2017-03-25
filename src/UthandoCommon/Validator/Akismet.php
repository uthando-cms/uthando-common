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
    const INVALID               = 'invalid';
    const INVALID_COMMENT_TYPE  = 'invalidComment';
    const SPAM                  = 'isSpam';

    const COMMENT_TYPE_BLOG_POST    = 'blog-post';
    const COMMENT_TYPE_COMMENT      = 'comment';
    const COMMENT_TYPE_CONTACT_FORM = 'contact-form';
    const COMMENT_TYPE_FORUM        = 'forum-post';
    const COMMENT_TYPE_MESSAGE      = 'message';
    const COMMENT_TYPE_REPLY        = 'reply';
    const COMMENT_TYPE_SIGNUP       = 'signup';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID               => 'Invalid input',
        self::SPAM                  => 'The text seems to be spam',
        self::INVALID_COMMENT_TYPE  => 'The comment type is not supported.'
    ];

    /**
     * @var array
     */
    protected $validCommentTypes = [
        self::COMMENT_TYPE_BLOG_POST,
        self::COMMENT_TYPE_COMMENT,
        self::COMMENT_TYPE_CONTACT_FORM,
        self::COMMENT_TYPE_FORUM,
        self::COMMENT_TYPE_MESSAGE,
        self::COMMENT_TYPE_REPLY,
        self::COMMENT_TYPE_SIGNUP,
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
     * URL submitted with the comment.
     *
     * @var string
     */
    protected $commentAuthorUrl = '';

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

    /**
     * Akismet constructor.
     *
     * @param array $options
     */
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

        if (array_key_exists('comment_author_url', $options)) {
            $this->setCommentAuthorUrl($options['comment_author_url']);
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
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return Akismet
     */
    public function setApiKey(string $apiKey): Akismet
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
    public function getBlog(): string
    {
        return $this->blog;
    }

    /**
     * @param string $blog
     * @return Akismet
     */
    public function setBlog(string $blog): Akismet
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
    public function getUserIp(): string
    {
        return $this->userIp;
    }

    /**
     * @param string $userIp
     * @return Akismet
     */
    public function setUserIp(string $userIp): Akismet
    {
        $this->userIp = $userIp;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentAuthor(): string
    {
        return $this->commentAuthor;
    }

    /**
     * @param string $commentAuthor
     * @return Akismet
     */
    public function setCommentAuthor(string $commentAuthor): Akismet
    {
        $this->commentAuthor = $commentAuthor;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentAuthorEmail(): string
    {
        return $this->commentAuthorEmail;
    }

    /**
     * @param string $commentAuthorEmail
     * @return Akismet
     */
    public function setCommentAuthorEmail(string $commentAuthorEmail): Akismet
    {
        $this->commentAuthorEmail = $commentAuthorEmail;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCommentAuthorUrl(): string
    {
        return $this->commentAuthorUrl;
    }

    /**
     * @param string $commentAuthorUrl
     * @return Akismet
     */
    public function setCommentAuthorUrl(string $commentAuthorUrl): Akismet
    {
        $this->commentAuthorUrl = $commentAuthorUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentType(): string
    {
        return $this->commentType;
    }

    /**
     * @param string $commentType
     * @return Akismet
     */
    public function setCommentType(string $commentType): Akismet
    {
        $this->commentType = $commentType;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     * @return Akismet
     */
    public function setUserAgent(string $userAgent): Akismet
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
    public function isValid($value, $context = null): bool
    {
        if (!is_array($context)) {
            $this->error(self::INVALID);
            return false;
        }

        if (!in_array($this->getCommentType(), $this->validCommentTypes)) {
            $this->error(self::INVALID_COMMENT_TYPE);
            return false;
        }

        $this->setValue($value);

        $akismet = new AkismetService($this->getApiKey(), $this->getBlog());

        if (!$akismet->verifyKey($this->getApiKey())) {
            throw new Exception\InvalidArgumentException('Invalid API key for Akismet');
        }

        $data = [
            'comment_type'          => $this->getCommentType(),
            'comment_author'        => $context[$this->getCommentAuthor()],
            'comment_author_email'  => $context[$this->getCommentAuthorEmail()],
            'comment_author_url'    => $context[$this->getCommentAuthorUrl()] ?? '',
            'comment_content'       => $value,
            'user_agent'            => $this->getUserAgent(),
            'user_ip'               => $this->getUserIp(),
        ];

        if ($akismet->isSpam($data)) {
            $this->error(self::SPAM);
            return false;
        }

        return true;
    }
}
