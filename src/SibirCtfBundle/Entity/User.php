<?php
namespace SibirCtfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SibirCtfBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="SibirCtfBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity="SibirCtfBundle\Entity\Mentor", inversedBy="profile", cascade={"remove"})
     */
    private $mentor = null;

    /**
     * @ORM\OneToOne(targetEntity="SibirCtfBundle\Entity\Watcher", inversedBy="profile", cascade={"remove"})
     */
    private $watcher = null;

    /**
     * @ORM\OneToOne(targetEntity="SibirCtfBundle\Entity\Member", inversedBy="profile", cascade={"remove"})
     */
    private $member = null;

    /**
     * @ORM\OneToOne(targetEntity="SibirCtfBundle\Entity\Speaker", inversedBy="profile", cascade={"remove"})
     */
    private $speaker = null;

    /**
     * @ORM\Column(type="text")
     */
    private $arrival_description = '';
    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;


    public function __construct()
    {
        $this->isActive = true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getMember()
    {
        return $this->member;
    }

    public function setMember($member)
    {
        $this->member = $member;

        return $this;
    }

    public function getWatcher()
    {
        return $this->watcher;
    }

    public function setWatcher($speaker)
    {
        $this->watcher = $speaker;

        return $this;
    }

    public function getMentor()
    {
        return $this->mentor;
    }

    public function setMentor($people)
    {
        $this->mentor = $people;

        return $this;
    }

    public function getSpeaker()
    {
        return $this->speaker;
    }

    public function setSpeaker($speaker)
    {
        $this->speaker = $speaker;

        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getArrivalDescription()
    {
        return $this->arrival_description;
    }

    public function setArrivalDescription($text)
    {
        $this->arrival_description = $text;

        return $this;
    }

    public function getMail()
    {
        return $this->email;
    }

    public function setMail($email)
    {
        $this->email = $email;

        return $this;
    }
    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles()
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function eraseCredentials() {}

    public function getSalt()
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one
    }
}